<?php

namespace Modules\Wallet\Database\Factories;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = \Modules\Wallet\Models\PaymentMethod::class;

    public function definition(): array
    {
        $fakerAr = \Faker\Factory::create('ar_SA');
        $fakerEn = $this->faker;

        return [
            'store_id' => Store::factory(),

            'name' => [
                'ar' => $fakerAr->randomElement(['بينانس', 'بايبال', 'تحويل بنكي']),
                'en' => $fakerEn->randomElement(['Binance', 'PayPal', 'Bank Transfer']),
            ],

            'currencies' => [
                'en' => ['USD', 'SYP', 'TRY'],
                'ar' => ['دولار أمريكي', 'الليرة السورية', 'الليرة التركية']
            ],


            'recipient_name' => [
                'ar' => 'ون كليك للتجارة الإلكترونية',
                'en' => 'One Click E-Commerce',
            ],

            'account_number' => $fakerEn->numerify('############'),
            'bank_name' => [
                'ar' => 'البنك الأهلي السعودي',
                'en' => 'AlAhli Bank',
            ],

            'transfer_number' => 'TRF-' . $fakerEn->unique()->numerify('2025-#####'),

            'instructions' => [
                'ar' => [
                    "تأكد من استخدام رقم التحويل المذكور أعلاه في وصف التحويل",
                    "احتفظ بإشعار التحويل الأصلي لمراجعة الطلب",
                    "سيتم معالجة طلبك خلال 24 ساعة عمل",
                    "تأكد من صحة المبلغ المدخل قبل إرسال الطلب"
                ],
                'en' => [
                    "Make sure to use the above transfer number in the transfer description",
                    "Keep the original transfer receipt for order review",
                    "The order will be processed within 24 working hours",
                    "Ensure the amount is correct before sending the transfer"
                ],
            ],

            'is_active' => true,
        ];
    }
}
