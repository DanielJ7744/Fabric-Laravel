<?php

namespace App\Console\Commands;

use App\Exceptions\AlertMailException;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AlertMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:mail_send {recipient} {--name=} {--type=} {--template=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send off sample of the alert mail by type';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws AlertMailException
     */
    public function handle(): void
    {
        $type = 'info';
        $template = 'emails.general';
        $recipient = $this->argument('recipient');
        $name = $this->option('name');
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw new AlertMailException('Recipient email address not valid!');
        }
        if (!empty($this->option('type'))) {
            $type = $this->option('type');
        }
        if (!empty($this->option('template'))) {
            $template = $this->option('template');
        }

        try {
            $this->sendMail($recipient, $name, $type, $template);
            $this->logPrintSystemMsg(sprintf('Mail has been sent to %s with type %s', $recipient, $type));
        } catch (\Exception $e) {
            throw new AlertMailException($e->getMessage());
        }
    }

    protected function sendMail($recipient, $name, $type, $template)
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $subject = sprintf('Alert type %s found', $type);
        $name = (empty($name)) ? 'User' : $name;
        $alert = [
            'alert_type' => $type,
            'service_id' => 12345678
        ];
        $metaData =  [
          "company_id" => 1,
          "company_name" => "Patchworks Test Account Manual",
          "company_email" => $recipient,
          "transaction_limit" => 15000,
          "transaction_total" => 20848,
          "exceeded_by" => 5848,
          "total_services" => 200,
          "active_services" => 56,
          "total_pull_usage" => 1240030,
          "active_users" => 4
        ];

        Mail::send(
            $template,
            [
                'alert' => $alert,
                'subject' => $subject,
                'timestamp' => $timestamp,
                'name' => $name,
                'meta' => $metaData
            ],
            function ($message) use ($recipient, $subject, $name) {
                $message->to($recipient, $name)->subject($subject);
            }
        );
    }

    /**
     * @param $message
     * @param string $logType
     */
    public function logPrintSystemMsg($message, $logType = 'info')
    {
        Log::channel('stack')->{$logType}($message);
    }
}
