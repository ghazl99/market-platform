<?php

namespace Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Modules\Order\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmOrderNotification extends Notification
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
        return [
            'title' => [
                'ar' => 'تأكيد الطلب',
                'en' => 'Order Confirmed',
            ],

            'message' => [
                'ar' => "تم تأكيد طلبك ",
                'en' => "Your order has been confirmed",
            ],
            'url' => route('order.show', $this->order->id),
            'order_id' => $this->order->id,
            'store_id' => $this->order->store_id,
        ];
    }
}
