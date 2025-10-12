<?php

namespace Modules\Wallet\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RejectedPaymentRequest extends Notification
{
    use Queueable;

    protected $paymentRequest;
    protected $notes;

    public function __construct($paymentRequest, ?string $notes = null)
    {
        $this->paymentRequest = $paymentRequest;
        $this->notes = $notes;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $amount = $this->paymentRequest->amount_usd;
        // $currency = $this->paymentRequest->amount_usd ;
        // $noteMessageAr = $this->notes ? "ملاحظة: {$this->notes}" : "";
        // $noteMessageEn = $this->notes ? "Note: {$this->notes}" : "";

        return [
            'title' => [
                'ar' => 'تم رفض طلب إضافة الرصيد',
                'en' => 'Balance Top-up Request Rejected',
            ],
            'message' => [
                'ar' => "تم رفض طلبك لإضافة مبلغ {$amount}",
                'en' => "Your request to add {$amount} has been rejected",
            ],
            'payment_request_id' => $this->paymentRequest->id,
            'wallet_id' => $this->paymentRequest->wallet_id,
            'url' => route('wallet.index'),
        ];
    }
}
