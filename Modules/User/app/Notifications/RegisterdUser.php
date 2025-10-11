<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegisterdUser extends Notification
{
    use Queueable;

    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'title' => [
                'ar' => 'تم إنشاء حسابك بنجاح.',
                'en' => 'Your account has been created successfully.',
            ],
            'message' => [
                'ar' => 'أهلاً بك في متجرنا,نتمنى لك تجربة ممتعة.',
                'en' => 'Welcome to our store, we hope you have a pleasant experience.',
            ],
            'url' => route('home')
        ];
    }
}
