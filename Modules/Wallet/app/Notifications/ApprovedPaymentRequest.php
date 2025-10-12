<?php

namespace Modules\Wallet\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovedPaymentRequest extends Notification
{
    use Queueable;

    protected $paymentRequest;

    public function __construct($paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $amount = $this->paymentRequest->amount_usd;

        return [
            'title' => [
                'ar' => 'تمت الموافقة على طلب إضافة الرصيد',
                'en' => 'Balance Top-up Request Approved',
            ],
            'message' => [
                'ar' => "تمت إضافة مبلغ {$amount}  إلى رصيدك بنجاح.",
                'en' => "An amount of {$amount} has been successfully added to your balance.",
            ],
            'payment_request_id' => $this->paymentRequest->id,
            'wallet_id' => $this->paymentRequest->wallet_id,
            'url' => route('wallet.index'),
        ];
    }
}
