<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmailNotification extends BaseVerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->view([
                'html' => 'emails.verify-email',
                'text' => 'emails.verify-email-text',
            ], [
                'appName' => config('app.name'),
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
