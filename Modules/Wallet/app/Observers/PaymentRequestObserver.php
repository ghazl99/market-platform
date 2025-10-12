<?php

namespace Modules\Wallet\Observers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\PaymentRequest;
use Modules\Notification\Services\FirebaseNotificationService;

class PaymentRequestObserver
{
    /**
     * Handle the PaymentRequest "created" event.
     */
    public function created(PaymentRequest $paymentRequest): void
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $owner = $store->owners()->first();

        if ($owner) {
            // إشعار قاعدة البيانات
            $owner->notify(new \Modules\Wallet\Notifications\StorePaymentRequest($paymentRequest));

            // Firebase notification
            $lang = App::getLocale(); // 'ar' أو 'en'
            $customerName = Auth::user()?->name ?? 'عميل غير معروف';

            $titles = [
                'ar' => 'طلب إضافة رصيد جديد',
                'en' => 'New Balance Top-up Request',
            ];

            $bodies = [
                'ar' => "قام العميل {$customerName} بطلب إضافة رصيد بمبلغ {$paymentRequest->amount_usd} \$",
                'en' => "Customer {$customerName} requested to add a balance of {$paymentRequest->amount_usd} \$",
            ];

            $data = [
                'title' => (string)($titles[$lang]),
                'body' => (string)($bodies[$lang]),
                'type' => 'payment_request',
                'payment_request_id' => $paymentRequest->id,
                'wallet_id' => $paymentRequest->wallet_id,
                'url' => route('dashboard.dashboard.payment-requests.index'),
                'icon' => asset('assets/img/payment.png'),
            ];
            FirebaseNotificationService::send($owner, $data);
        }
    }

    /**
     * Handle the PaymentRequest "updated" event.
     */
    public function updated(PaymentRequest $paymentRequest): void
    {
        $paymentRequest->loadMissing('wallet.user'); // تحميل العلاقة إذا لم تكن محملة

        $user = $paymentRequest->wallet?->user;
        if (! $user) return;

        $lang = App::getLocale();

        $titlesApproved = [
            'ar' => 'تمت الموافقة على طلبك',
            'en' => 'Your payment request was approved',
        ];

        $titlesRejected = [
            'ar' => 'تم رفض طلبك',
            'en' => 'Your payment request was rejected',
        ];

        $bodiesApproved = [
            'ar' => "تمت إضافة المبلغ إلى رصيدك بنجاح",
            'en' => "Amount has been added to your balance successfully",
        ];

        $bodiesRejected = [
            'ar' => "تم رفض طلبك لإضافة المبلغ",
            'en' => "Your request to add has been rejected",
        ];

        if ($paymentRequest->status === 'approved') {
            $user->notify(new \Modules\Wallet\Notifications\ApprovedPaymentRequest($paymentRequest));
            $data = [
                'title' => (string)($titlesApproved[$lang]),
                'body'  => (string)($bodiesApproved[$lang]),
                'type'  => 'payment_request',
                'payment_request_id' => $paymentRequest->id,
                'wallet_id' => $paymentRequest->wallet_id,
                'url' => route('wallet.index'),
                'icon' => asset('assets/img/payment.png'),
            ];
            // dd($data);
            FirebaseNotificationService::send($user, $data);
        }

        if ($paymentRequest->status === 'rejected') {
            $user->notify(new \Modules\Wallet\Notifications\RejectedPaymentRequest($paymentRequest, $paymentRequest->notes ?? null));
            $data = [
                'title' => (string)($titlesRejected[$lang]),
                'body'  => (string)($bodiesRejected[$lang]),
                'type'  => 'payment_request',
                'payment_request_id' => $paymentRequest->id,
                'wallet_id' => $paymentRequest->wallet_id,
                'url' => route('wallet.index'),
                'icon' => asset('assets/img/payment.png'),
            ];
            FirebaseNotificationService::send($user, $data);
        }
    }


    public function deleted(PaymentRequest $paymentRequest): void {}
    public function restored(PaymentRequest $paymentRequest): void {}
    public function forceDeleted(PaymentRequest $paymentRequest): void {}
}
