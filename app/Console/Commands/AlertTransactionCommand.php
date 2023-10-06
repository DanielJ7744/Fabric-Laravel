<?php

namespace App\Console\Commands;

use App\System\Helpers\AlertSchedulerHelper;
use Illuminate\Console\Command;

class AlertTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alert manager for companies exceeding transaction limits';

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
     */
    public function handle() : void
    {
        $alerts = $this->alertScheduleHelper->getTransactionLimitAlerts();
        $alertCount = count($alerts);
        $this->alertScheduleHelper->logPrintSystemMsg(sprintf('AT: Found %d transaction limit alerts to schedule', $alertCount));

        if ($alertCount > 0) {
            $alerts->map(function ($alert) {
                $this->alertScheduleHelper->logPrintSystemMsg(sprintf('AT: Creating schedule record for company ID: %d ..', $alert['company_id']));
                $this->alertScheduleHelper->createTransactionLimitAlertManagerRecords($alert);
                $this->alertScheduleHelper->logPrintSystemMsg(sprintf('AT: Company ID %d alert schedule created.', $alert['company_id']));
            });
        }
        $this->alertScheduleHelper->logPrintSystemMsg('AT: Finished transaction limit alert scheduling.');
    }
}
