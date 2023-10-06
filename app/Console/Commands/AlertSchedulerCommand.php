<?php

namespace App\Console\Commands;

use App\System\Helpers\AlertSchedulerHelper;
use Illuminate\Console\Command;

class AlertSchedulerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alert scheduler for services';

    protected AlertSchedulerHelper $alertScheduleHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->alertScheduleHelper = new AlertSchedulerHelper();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() : void
    {
        $alerts = $this->alertScheduleHelper->getAlerts();
        $alertCount = count($alerts);
        $this->alertScheduleHelper->logPrintSystemMsg(sprintf('Found %d alerts to schedule', $alertCount));
        if ($alertCount > 0) {
            $alerts->map(function ($alert) {
                $this->alertScheduleHelper->logPrintSystemMsg(sprintf('Creating schedule record for service ID: %d ..', $alert->service_id));
                $this->alertScheduleHelper->createAlertManagerRecords($alert);
                $this->alertScheduleHelper->logPrintSystemMsg(sprintf('Service ID: %d alert schedule created.', $alert->service_id));
            });
        }
        $this->alertScheduleHelper->logPrintSystemMsg('Finished alert scheduling.');
    }
}
