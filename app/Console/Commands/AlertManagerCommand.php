<?php

namespace App\Console\Commands;

use App\Jobs\ProcessAlertsJob;
use App\System\Helpers\AlertManagerHelper;
use Illuminate\Console\Command;

class AlertManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alert manager for scheduled alerts';

    protected AlertManagerHelper $alertHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->alertHelper = new AlertManagerHelper();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle() : void
    {
        $alerts = $this->alertHelper->checkAndSetAlerts()->getAlerts();

        if ($this->alertHelper->isActiveAlerts()) {
            $this->alertHelper->logPrintSystemMsg(sprintf('Found %d alerts to send', $this->alertHelper->countActiveAlerts()));
            $alerts->map(function($alert) {
                $this->alertHelper->updateAlertStatus($alert['id']);
                dispatch((new ProcessAlertsJob($alert, $this->alertHelper))->delay(now()->addSeconds(2)));
            });
            $this->alertHelper->logPrintSystemMsg('Completed sending mails for all active alerts.');
            return;
        }

        $this->alertHelper->logPrintSystemMsg('No active alerts currently');
    }
}
