<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('重設密碼通知')
            ->greeting('親愛的用戶，您好！')
            ->line('我們收到了您的帳號密碼重設請求。')
            ->line('您可以點擊下方按鈕進行密碼重設：')
            ->action('重設密碼', url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('此密碼重設連結將在 60 分鐘後失效。')
            ->line('如果您沒有提出重設密碼的請求，請忽略此郵件。')
            ->salutation('祝 安好');
    }
}