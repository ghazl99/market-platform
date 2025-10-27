<?php

namespace Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Modules\Order\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    use Queueable;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        // الحصول على اسم المستخدم من الطلب نفسه، وليس من المستخدم الحالي
        $userName = $this->order->user?->name ?? 'مستخدم غير معروف';
        $productName = $this->order->items()->first()?->product?->name ?? 'منتج';
        return [
            'title' => [
                'ar' => 'طلب جديد',
                'en' => 'New Order',
            ],
            'message' => [
                'ar' => "{$userName} قام بطلب {$productName}",
                'en' => "{$userName} placed an order for {$productName}",
            ],
            'url' => route('dashboard.order.show', $this->order->id),
            'order_id' => $this->order->id,
            'store_id' => $this->order->store_id,
        ];
    }
}
