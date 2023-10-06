<?php
namespace App\System\Helpers;


use App\Models\Alerting\AlertManager;
use App\Models\Alerting\AlertRecipients;
use App\Models\Fabric\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlertManagerHelper
{
    /**
     * alerts to attempt to schedule
     *
     * @var Collection|array
     */
    protected $alerts = [];

    public function checkAndSetAlerts() : AlertManagerHelper
    {
        $this->logPrintSystemMsg('Getting alerts eligible for sending');
        $this->alerts = $this->getActiveAlerts();
        return $this;
    }

    public function getAlerts() : Collection
    {
        return $this->alerts;
    }

    /**
     * @param $message
     * @param string $logType
     */
    public function logPrintSystemMsg($message, $logType = 'info')
    {
        Log::channel('stack')->{$logType}($message);
    }

    public function isActiveAlerts() : bool
    {
        return $this->alerts->count() > 0;
    }

    public function countActiveAlerts() : int
    {
        return $this->alerts->count();
    }

    /**
     * @return Collection
     */
    protected function getActiveAlerts() : Collection
    {
        $alerts = new AlertManager();
        return $alerts
            ->whereNull('dispatched_at')
            ->whereNull('processed_at')
            ->whereNull('failed_at')
            ->whereNotNull('send_from')
            ->where('send_from', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('send_from')->orderBy('service_id', 'desc')
            ->get()
            ->take(500);
    }

    public function getRecipientDetails(AlertManager $alert) : array
    {
        $template = 'emails.general';
        if (!is_null($alert->recipient_email)) {
            return [
                'name' => 'Administrator',
                'email' => $alert->recipient_email,
                'template' => $alert->email_template
            ];
        }
        $recipient = DB::table('alert_recipients')
            ->leftJoin('users', 'users.id', '=', 'alert_recipients.user_id')
            ->where('alert_recipients.disabled', 0)
            ->where('alert_recipients.id', $alert->recipient_id)
            ->get([
                'users.name AS user_name',
                'users.email AS user_email',
                'alert_recipients.name',
                'alert_recipients.email'
            ])->first();
        if (empty($recipient)) {
            return [];
        }
        if ($recipient->email) {
            return [
                'name' => $recipient->name,
                'email' => $recipient->email,
                'template' => $template
            ];
        }
        return [
            'name' => $recipient->user_name,
            'email' => $recipient->user_email,
            'template' => $template
        ];
    }

    public function updateAlertStatus(int $alertId, string $status = 'default') : void
    {
        $alert = AlertManager::findOrFail($alertId);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        switch($status) {
            case 'failed':
                $alert->failed_at = $now;
                break;
            case 'success':
                $alert->processed_at = $now;
                break;
            case 'default':
                $alert->dispatched_at = $now;
                break;
        }
        $alert->save();
    }
}
