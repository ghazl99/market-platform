<?php

namespace Modules\Wallet\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StorePaymentRequest extends Notification
{
    use Queueable;
    protected $paymentRequest;
    /**
     * Create a new notification instance.
     */
    public function __construct($paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
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
        $customerName = Auth::user()?->name ?? 'عميل غير معروف';

        return [
            'payment_request_id' => $this->paymentRequest->id,
            'title' => [
                'ar' => 'طلب إضافة رصيد',
                'en' => 'Balance Top-up Request',
            ],
            'message' => [
                'ar' => "قام العميل {$customerName} بطلب إضافة رصيد بمبلغ {$this->paymentRequest->amount_usd} .",
                'en' => "Customer {$customerName} has requested to add a balance of {$this->paymentRequest->amount_usd} .",
            ],
            'url' => route('dashboard.dashboard.payment-requests.index'),
        ];
    }
}
