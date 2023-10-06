<?php
namespace App\System\Helpers;

use App\Models\Alerting\AlertConfigs;
use App\Models\Alerting\AlertManager;
use App\Models\Alerting\AlertRecipients;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Tapestry\ServiceLog;
use Carbon\Carbon;
use Cron\CronExpression;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AlertSchedulerHelper
{
    /**
     * alerts to attempt to schedule
     *
     * @var Collection|array
     */
    protected $alertConfigs = [];
    protected $alertTransactions = [];

    private Client $client;

    private LaravelCollection $alertTotalStatus;

    private CronExpression $cronExpression;

    private LaravelCollection $apiResults;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return array|Collection|LaravelCollection
     */
    public function getAlerts()
    {
        $this->logPrintSystemMsg('Getting alerts eligible for scheduling');
        $this->alertConfigs = $this->getAlertConfigs();
        $alerts = [];
        if (count($this->alertConfigs) == 0) {
            return $alerts;
        }

        $this->logPrintSystemMsg(sprintf('Found %s eligible alerts', count($this->alertConfigs)));
        return $this->alertConfigs->map(function($item) {
            if (!$this->hasCronSchedule($item)) {
                return false;
            }
            try {
                $this->setCronExpression($item->alert_frequency);
                if (!$this->checkCronSchedule($item)) {
                    return false;
                }
                $this->logPrintSystemMsg(sprintf('Getting api results for service ID: %d', $item->service_id));
                $timestamp = $this->getTimeStampFromCronExpression();
                $this->apiResults = ServiceLog::serviceErrors($item->service_id, $timestamp)->orderBy('id', 'desc')
                    ->get([
                        'id',
                        'service_id',
                        'username',
                        'status',
                        'started_at',
                        'finished_at',
                        'error',
                        'warning',
                        'other',
                    ]);
                $this->logPrintSystemMsg(sprintf('Api results found %d results for service ID: %d', $this->apiResults->count(), $item->service_id));
                if ($this->validateAlertConfig($item, $this->apiResults)->getAlertStatus()) {
                    return $item;
                }
                return false;
            } catch (GuzzleException $e) {
                $this->logPrintSystemMsg($e->getMessage(), 'error');
                return false;
            }
        })->reject(function ($value) {
            return $value === false;
        });
    }

    /**
     * @return array|LaravelCollection
     */
    public function getTransactionLimitAlerts()
    {
        $this->logPrintSystemMsg('Getting transaction limit alerts eligible for scheduling');
        $this->alertTransactions = $this->getExceededTransactionLimits();
        $alerts = [];
        if (count($this->alertTransactions) == 0) {
            return $alerts;
        }
        $this->logPrintSystemMsg(sprintf('Found %s eligible alerts', count($this->alertTransactions)));
        return collect($this->alertTransactions);
    }

    /**
     * @param AlertConfigs $alertConfig
     * @return bool
     */
    public function createAlertManagerRecords(AlertConfigs $alertConfig) : bool
    {
        try {
            $alertRecipients = $this->getServiceRecipients($alertConfig->service_id);
            if ($alertRecipients->count() == 0) {
                $this->logPrintSystemMsg(sprintf('No recipients have been found for service id: %d', $alertConfig->service_id));
                exit();
            }
            $this->updateAlertConfig($alertConfig);
            collect($alertRecipients)->map(function($recipient) use($alertConfig) {
                $alertTypesFound = $this->getAlertType($alertConfig);
                foreach ($alertTypesFound as $type) {
                    $alertManager = new AlertManager();
                    $alertManager->company_id = $alertConfig->company_id;
                    $alertManager->service_id = $alertConfig->service_id;
                    $alertManager->config_id = $alertConfig->id;
                    $alertManager->recipient_id = $recipient->id;
                    $alertManager->alert_type = $type;
                    $alertManager->send_from = Carbon::now()->format('Y-m-d H:i:s');
                    $alertManager->service_log_run_ids = json_encode($this->getServiceLogRunIds($type, $alertConfig));
                    $alertManager->save();
                }
            });
            return true;
        } catch (ModelNotFoundException $e) {
            $this->logPrintSystemMsg($e->getMessage());
            return false;
        }
    }

    /**
     * @param LaravelCollection $alertTransaction
     * @return bool
     */
    public function createTransactionLimitAlertManagerRecords(LaravelCollection $alertTransaction) : bool
    {
        $listEmails = [env('ALERT_TRANSACTION_EXCEED_EMAIL', 'sales@patchworks.co.uk')];
        $listEmails[] = $alertTransaction->get('company_email');
        $this->logPrintSystemMsg(sprintf('AT: Creating entries for mails - ', json_encode($listEmails)));
        try {
            foreach($listEmails as $email) {
                $alertManager = new AlertManager();
                $alertManager->company_id = $alertTransaction->get('company_id');
                $alertManager->service_id = 0;
                $alertManager->config_id = 0;
                $alertManager->recipient_id = 0;
                $alertManager->recipient_email = $email;
                $alertManager->meta_data = $alertTransaction->toJson();
                $alertManager->email_template = 'emails.alerts.transaction-limit';
                $alertManager->alert_type = 'warning';
                $alertManager->send_from = Carbon::now()->format('Y-m-d H:i:s');
                $alertManager->service_log_run_ids = NULL;
                $alertManager->save();
            }

            return true;

        } catch (ModelNotFoundException $e) {
            $this->logPrintSystemMsg($e->getMessage());
            return false;
        }
    }

    /**
     * Get the service log run IDs for the type of alert manager being saved
     *
     * @param string $type
     * @param AlertConfigs $alertConfig
     *
     * @return array
     */
    private function getServiceLogRunIds(string $type, AlertConfigs $alertConfig): array
    {
        $apiResults = $this->apiResults->where('service_id', $alertConfig->service_id);
        if ($type === 'error' && $alertConfig->error_alert_status === 1) {
            $resultsByType = $apiResults->where('error', '>=', $alertConfig->error_alert_threshold);

            return $resultsByType->pluck('username', 'id')->all();
        }

        if ($type === 'warning' && $alertConfig->warning_alert_status === 1) {
            $resultsByType = $apiResults->where('warning', '>=', $alertConfig->warning_alert_threshold);

            return $resultsByType->pluck('username', 'id')->all();
        }

        return $apiResults->pluck('username', 'id')->all();
    }

    public function getServiceRecipients(int $serviceId) : Collection
    {
        return AlertRecipients::whereHas('recipients', function (Builder $query) use($serviceId) {
            $query->where('service_id', '=', $serviceId);
        })->get();
    }

    private function updateAlertConfig(AlertConfigs $alertConfig) : void {
        $alertConfig = AlertConfigs::find($alertConfig->id);
        $alertConfig->alert_scheduled = 1;
        $alertConfig->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $alertConfig->save();
    }

    private function getAlertType(AlertConfigs $alertConfig) : array
    {
        $alertTypes = [];
        if ($alertConfig->error_alert_status == 1) {
            $alertTypes[]  = 'error';
        }
        if ($alertConfig->warning_alert_status == 1) {
            $alertTypes[] = 'warning';
        }
        if ($alertConfig->frequency_alert_status == 1) {
            $alertTypes[] = 'info';
        }
        return $alertTypes;
    }

    /**
     * @param AlertConfigs $config
     * @param LaravelCollection $apiResults
     * @return $this
     */
    protected function validateAlertConfig(AlertConfigs $config, LaravelCollection $apiResults) : AlertSchedulerHelper
    {
        $apiAggregatedArray = collect($this->getAlertGrouping($apiResults, $config->service_id))->first();
        if ($apiResults->isEmpty()) {
            $this->alertTotalStatus = collect([]);
            return $this;
        }
        $this->alertTotalStatus = collect($config)->map(function($item, $key) use($config, $apiAggregatedArray) {
            if ($key === 'error_alert_threshold') {
                if ($apiAggregatedArray['error_total'] >= $config[$key] && $config[$key] !== 0) {
                    $config['error_alert_status'] = 1;
                    $config['threshold_activated'] = true;
                    return $config;
                }
            }
            if ($key === 'warning_alert_threshold') {
                if ($apiAggregatedArray['warning_total'] >= $config[$key] && $config[$key] !== 0) {
                    $config['warning_alert_status'] = 1;
                    $config['threshold_activated'] = true;
                    return $config;
                }

            }
            if ($key === 'frequency_alert_status') {
                if ($apiAggregatedArray['other_total'] >= $config[$key] && $config[$key] !== 0) {
                    $config['frequency_alert_status'] = 1;
                    $config['threshold_activated'] = true;
                    return $config;
                }
            }
            return false;
        })->reject(function ($value) {
            return $value === false;
        });

        return $this;
    }

    protected function getAlertStatus() : bool {

        if ($this->alertTotalStatus->isEmpty()) {
            return false;
        }
        if (collect($this->alertTotalStatus->first())->has('threshold_activated')) {
            $this->checkAlertStatus();
            return true;
        }
        $this->checkAlertStatus(true);
        return false;
    }

    private function checkAlertStatus($reset = false) : void
    {
        $alertCollection = collect($this->alertTotalStatus->first());
        if ($reset) {
            $this->updateAlertStatus($alertCollection['id'], 0, 0, 0);
        } else {
            $errorStatus = ($alertCollection['error_alert_status'] == 1) ? 1 : 0;
            $warningStatus = ($alertCollection['warning_alert_status'] == 1) ? 1 : 0;
            $frequencyStatus = ($alertCollection['frequency_alert_status'] == 1) ? 1 : 0;
            $this->updateAlertStatus($alertCollection['id'], $errorStatus, $warningStatus, $frequencyStatus);
        }
    }

    private function updateAlertStatus(int $alertId, int $errorStatus, int $warningStatus, int $frequencyStatus) : void
    {
        $alert = AlertConfigs::findOrFail($alertId);
        $alert->error_alert_status = $errorStatus;
        $alert->warning_alert_status = $warningStatus;
        $alert->frequency_alert_status = $frequencyStatus;
        $alert->save();
    }

    /**
     * @param LaravelCollection $apiResults
     * @param int $serviceId
     * @return array
     */
    private function getAlertGrouping(LaravelCollection $apiResults, int $serviceId): array
    {
        $aggregatedAlerts[$serviceId] = [
            'error_total' => 0,
            'warning_total' => 0,
            'other_total' => 0,
        ];

        foreach($apiResults->toArray() as $item) {
            if ($item['status'] == 'running') {
                continue;
            }
            foreach ($item as $key => $val) {
                switch ($key) {
                    case 'error':
                        $aggregatedAlerts[$serviceId]['error_total'] += $val;
                        break;
                    case 'warning':
                        $aggregatedAlerts[$serviceId]['warning_total'] += $val;
                        break;
                    case 'other':
                        $aggregatedAlerts[$serviceId]['other_total'] += $val;
                        break;
                }
                $aggregatedAlerts[$serviceId]['service_id'] = $serviceId;
            }
        }
        return $aggregatedAlerts;
    }

    /**
     * Get all integrations for company, unless specific ids specified
     * @param int $companyId
     * @param array|null $ids
     * @return Model
     */
    public function getIntegrationsByCompanyId (int $companyId): Integration
    {
        return Integration::where('company_id', $companyId)->firstOrFail();
    }

    /**
     * Make request to Tapestry
     *
     * @param string $url
     *
     * @return LaravelCollection
     */
    private function makeGetRequest(string $url): LaravelCollection
    {
        try {
            return collect(json_decode($this->client->get($url)->getBody()->getContents(), true)['items']);
        } catch (GuzzleException $exception) {
            return collect([]);
        }
    }

    /**
     * Has valid Cron Schedule?
     *
     * @param AlertConfigs $item
     * @return bool
     */
    public function hasCronSchedule(AlertConfigs $item): bool
    {
        return isset($item->alert_frequency) && !empty($item) && CronExpression::isValidExpression($item->alert_frequency);
    }

    /**
     * @param $message
     * @param string $logType
     */
    public function logPrintSystemMsg($message, $logType = 'info')
    {
        Log::channel('stack')->{$logType}($message);
    }

    /**
     * @return int
     */
    public function getTimeStampFromCronExpression(): int
    {
        return $this->cronExpression->getPreviousRunDate()->getTimestamp();
    }

    public function checkCronSchedule(AlertConfigs $alert) : bool
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $cronNextRunDate = $this->cronExpression->getNextRunDate()->format('Y-m-d H:i:s');
        if ($alert['next_run_datetime'] === null) {
            $this->updateNextRunTime($alert['id'], $cronNextRunDate, $now);
            return false;
        }
        if (strtotime($alert['next_run_datetime']) <= strtotime($now)) {
            $this->updateNextRunTime($alert['id'], $cronNextRunDate, $now,  1);
            return true;
        }
        return false;
    }

    private function updateNextRunTime(int $alertConfigId, string $cronNextRunDate, string $now, $schedule = 0)
    {
        $alert = AlertConfigs::findOrFail($alertConfigId);
        $alert->next_run_datetime = $cronNextRunDate;
        $alert->alert_scheduled = $schedule;
        $alert->updated_at = $now;
        $alert->save();
    }

    public function setCronExpression($cronExpression)
    {
        $this->cronExpression = CronExpression::factory($cronExpression);
        return $this;
    }

    /**
     * @return Collection
     */
    protected function getAlertConfigs() : Collection
    {
        $alert = new AlertConfigs();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        return $alert
            ->where('alert_status', 1)
            ->where('alert_scheduled', 0)
            ->where(function($q) use($now) {
                $q->where('next_run_datetime', '<=', $now)->orWhereNull('next_run_datetime');
            })
            ->whereNotIn('alert_frequency', ['off'])
            ->get();
    }

    /**
     * @return LaravelCollection
     */
    protected function getExceededTransactionLimits() : LaravelCollection
    {
        $companies = Company::where('active', 1)->get();

        $companiesCol = $companies->map(function($company) {
            try {
                $transactionLimit = $company->subscriptions->sum('transactions');
                $totalServices = $company->subscriptions->sum('services');
                $activeUsers = $company->subscriptionUsage()->active_users;
                $activeServices = $company->subscriptionUsage()->active_services;
                $totalTransactions = $company->subscriptionUsage()->transaction_count;
                $totalPullDataSize = $company->subscriptionUsage()->total_pull_data_size;

                if ((int)$totalTransactions == 0) {
                    $this->logPrintSystemMsg(sprintf('AT: Total transaction limit found for company ID: %d is - %d',
                        $company->id, $totalTransactions
                    ));
                    return false;
                }

                if ((int)$totalTransactions > (int)$transactionLimit) {
                    $this->logPrintSystemMsg(sprintf('AT: Found exceeded transaction limit for company ID: %d', $company->id));
                    return collect([
                        'company_id' => $company->id,
                        'company_name' => $this->getCompanyName($company->id),
                        'company_email' => $company->company_email,
                        'transaction_limit' => $transactionLimit,
                        'transaction_total' => $totalTransactions,
                        'exceeded_by' => $totalTransactions - $transactionLimit,
                        'total_services' => $totalServices,
                        'active_services' => $activeServices,
                        'total_pull_usage' => $totalPullDataSize,
                        'active_users' => $activeUsers,
                    ]);
                };

                return false;

            } catch (GuzzleException $e) {
                $this->logPrintSystemMsg($e->getMessage(), 'error');
                return false;
            }
        })->reject(function ($value) {
            return $value === false;
        });

        return collect($companiesCol);
    }

    /**
     * @param $companyId
     * @return string
     */
    public function getCompanyName($companyId)
    {
        return Cache::remember($companyId, 5, fn () => Company::where('id', $companyId)->value('name'));
    }
}
