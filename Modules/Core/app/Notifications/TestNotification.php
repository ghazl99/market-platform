<?php

namespace Modules\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            'title' => [
                'ar' => 'إشعار تجريبي',
                'en' => 'Test Notification'
            ],
            'message' => [
                'ar' => 'هذا إشعار تجريبي لاختبار نظام الإشعارات في لوحة التحكم. يمكنك حذفه أو تمييزه كمقروء.',
                'en' => 'This is a test notification to test the notification system in the dashboard. You can delete it or mark it as read.'
            ],
            'url' => route('dashboard.notifications'),
            'type' => 'test',
            'test_data' => [
                'created_at' => now()->toDateTimeString(),
                'random_id' => rand(1000, 9999),
                'version' => '1.0.0'
            ]
        ];
    }
}
