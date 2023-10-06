<?php
declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserAccountNotification extends Notification
{
    use Queueable;
    private $token;

    public function __construct(string $passwordResetToken)
    {
        $this->token = $passwordResetToken;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetPasswordUrl = url(sprintf(
            '%s/%s?token=%s&new-user=true',
            config('dashboard.url'),
            config('fabric.user_reset_password_url'),
            $this->token
        ));

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Patchworks - Welcome to your Dashboard')
            ->line('A new account has been created for you on the Patchworks Dashboard. Please create your password.')
            ->action('Set your Password', $resetPasswordUrl)
            ->line('Please contact support if you require any assistance.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
