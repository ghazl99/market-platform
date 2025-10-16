<?php

namespace Modules\Order\Observers;

use Modules\Order\Models\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Notifications\NewOrderNotification;
use Modules\Order\Notifications\ConfirmOrderNotification;
use Modules\Notification\Services\FirebaseNotificationService;


class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $owner = $order->store?->owners()?->first();

        if (!$owner) {
            return; // لا يوجد مالك
        }
        $order->load('items.product');
        // إرسال إشعار للمالك
        $owner->notify(new NewOrderNotification($order));
        $lang = App::getLocale(); // 'ar' أو 'en'
        $customerName = Auth::user()?->name ?? 'عميل غير معروف';
        $productName = $order->items()->first()?->product?->name ?? 'منتج';

        $titles = [
            'ar' => 'طلب جديد',
            'en' => 'New Order',
        ];

        $bodies = [
            'ar' => "قام العميل {$customerName} بطلب {$productName}",
            'en' => "Customer {$customerName} placed an order for {$productName}",
        ];

        $data = [
            'title' => (string)($titles[$lang]),
            'body' => (string)($bodies[$lang]),
            'type' => 'new_order',
            'order_id' => $order->id,
            'store_id' => $order->store_id,
            'url' => route('dashboard.order.show', $order->id),
            'icon' => asset('assets/img/order.png'),
        ];

        FirebaseNotificationService::send($owner, $data);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // تحقق أن الحالة أصبحت confirmed ولم تكن كذلك من قبل
        if ($order->isDirty('status') && $order->status === 'confirmed') {

            // العميل الذي طلب
            $customer = $order->pharmacist ?? $order->user ?? null; // عدل حسب علاقة المستخدم عندك

            // مالك المتجر
            $owner = $order->store?->owners()?->first();

            if ($customer && $owner) {
                // إرسال إشعار داخل النظام
                $customer->notify(new ConfirmOrderNotification($order));

                // إعداد بيانات لإشعار Firebase أيضًا
                $lang = App::getLocale();
                $storeName = $order->store?->name ?? 'المتجر';

                $titles = [
                    'ar' => 'تأكيد الطلب',
                    'en' => 'Order Confirmed',
                ];

                $bodies = [
                    'ar' => "تم تأكيد طلبك ",
                    'en' => "Your order has been confirmed",
                ];

                $data = [
                    'title' => (string)($titles[$lang]),
                    'body' => (string)($bodies[$lang]),
                    'type' => 'order_confirmed',
                    'order_id' => $order->id,
                    'store_id' => $order->store_id,
                    'url' => route('order.show', $order->id) ,
                    'icon' => asset('assets/img/order-confirmed.png'),
                ];

                FirebaseNotificationService::send($customer, $data);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void {}

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void {}

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void {}
}
