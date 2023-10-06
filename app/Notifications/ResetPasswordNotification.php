<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    private string $token;

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
            '%s/%s?token=%s&new-user=false',
            config('dashboard.url'),
            config('fabric.user_reset_password_url'),
            $this->token
        ));

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Patchworks - Reset your Password')
            ->line('A password reset has been requested for your account. Please reset your password.')
            ->action('Reset your Password', $resetPasswordUrl)
            ->line('Please contact support if you did not request a password reset.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
