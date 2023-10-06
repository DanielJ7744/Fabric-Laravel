<?php

namespace App\Jobs;

use App\Models\Alerting\AlertManager;
use App\System\Helpers\AlertManagerHelper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessAlertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected AlertManagerHelper $alertManagerHelper;
    protected AlertManager $alert;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AlertManager $alert, AlertManagerHelper $alertManagerHelper)
    {
        $this->alert = $alert;
        $this->alertManagerHelper = $alertManagerHelper;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $recipientDetails = $this->alertManagerHelper->getRecipientDetails($this->alert);

            if (!isset($recipientDetails['email'])) {
                throw new \Exception(sprintf('No email has been detected for alert %s', json_encode($this->alert)));
            }
            $timestamp = Carbon::now()->format('Y-m-d H:i:s');
            $subject = sprintf('Alert type %s found', $this->alert['alert_type']);
            $name = (empty($recipientDetails['name'])) ? 'User' : $recipientDetails['name'];

            $metaArray = $this->getMetaValues(
                'exceeded_by', 'transaction_limit', 'transaction_total',
                'active_services', 'total_services', 'company_name'
            );

            Mail::send(
                $recipientDetails['template'],
                [
                    'alert' => $this->alert,
                    'subject' => $subject,
                    'timestamp' => $timestamp,
                    'name' => $name,
                    'meta' => $metaArray,
                ],
                function($message) use($recipientDetails, $subject, $name) {
                    $message->to($recipientDetails['email'], $name)
                        ->subject($subject);
            });

            $this->alertManagerHelper->updateAlertStatus($this->alert['id'], 'success');
            $this->alertManagerHelper->logPrintSystemMsg(sprintf('Alert mail sent to: %s', $recipientDetails['email']));
        } catch (\Exception $e) {
            $this->alertManagerHelper->updateAlertStatus($this->alert['id'], 'failed');
            $this->alertManagerHelper->logPrintSystemMsg(sprintf('Alert mail error: %s', $e->getMessage()));
        }
    }

    /**
     * @param ...$keys
     * @return array
     */
    private function getMetaValues(...$keys) : array
    {
        if ($this->alert['meta_data'] == null) {
            return [];
        }
        $json = json_decode($this->alert['meta_data'], 0);

        if (!empty($keys)) {
            $metaInfo = [];
            foreach ($keys as $key) {
                if (isset($json->$key)) {
                    $metaInfo[$key] = $json->$key;
                }
            }
            return $metaInfo;
        }
        return (array) $json;
    }
}
