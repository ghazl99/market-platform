<?php

namespace Modules\Wallet\Services\Dashboard;

use Modules\Wallet\Models\PaymentMethod;
use Modules\Store\Models\Store;
use Modules\Wallet\Repositories\App\PaymentMethodRepository;
use Modules\Core\Traits\TranslatableTrait;
use Modules\Core\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentMethodService
{
    use TranslatableTrait, ImageTrait;

    public function __construct(
        protected PaymentMethodRepository $paymentMethodRepository
    ) {}

    /**
     * Get payment methods for a store
     */
    public function getForStore(Store $store)
    {
        return $this->paymentMethodRepository->getByStore($store);
    }

    /**
     * Prepare data for multilingual fields (name, recipient_name, bank_name, instructions)
     * Translate given fields to all supported languages automatically.
     *
     * @param  array  $data  Data array containing fields to translate
     * @param  array|null  $fields  Fields to translate, if null will translate all string fields
     * @return array Data with translated fields
     */
    private function prepareData(array $data, ?array $fields = null): array
    {
        $locale = app()->getLocale();

        // إذا لم يتم تحديد الحقول، استخدم الحقول الافتراضية
        if ($fields === null) {
            $fields = ['name', 'recipient_name', 'bank_name', 'instructions']; // الحقول الافتراضية للترجمة
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                // إذا كان الحقل array بالفعل (multi-language)، استخرج النسخة الحالية
                if (is_array($data[$field])) {
                    $original = $data[$field][$locale] ?? reset($data[$field]);
                } else {
                    $original = $data[$field];
                }

                // ضمان أن يكون string
                if (!is_string($original)) {
                    $original = (string)($original ?? '');
                }

                // إذا كان النص فارغاً، تخطاه
                if (empty(trim($original))) {
                    continue;
                }

                // حفظ النص الأصلي باللغة الحالية
                $translated = [$locale => $original];

                // ترجمة إلى جميع اللغات الأخرى
                foreach ($this->otherLangs() as $lang) {
                    try {
                        // استخدام Google Translate للترجمة التلقائية
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: " . $e->getMessage());
                        // في حالة فشل الترجمة، استخدم النص الأصلي كبديل
                        $translated[$lang] = $original;
                    }
                }

                // استبدال الحقل بنسخة متعددة اللغات
                $data[$field] = $translated;
            }
        }

        return $data;
    }

    /**
     * Create a new payment method
     */
    public function create(array $data, Store $store)
    {
        DB::beginTransaction();

        try {
            $locale = app()->getLocale();
            $createData = [
                'store_id' => $store->id,
                'is_active' => $data['is_active'] ?? true,
            ];

            // معالجة name (مطلوب)
            if (empty($data['name'])) {
                throw new \Exception('Name field is required');
            }

            $createData['name'] = [$locale => trim($data['name'])];
            try {
                foreach ($this->otherLangs() as $lang) {
                    try {
                        $createData['name'][$lang] = $this->autoGoogleTranslator($lang, $data['name']);
                    } catch (\Exception $e) {
                        Log::warning("Translation failed for name to $lang: " . $e->getMessage());
                        $createData['name'][$lang] = $data['name'];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Translation failed for name: " . $e->getMessage());
                foreach ($this->otherLangs() as $lang) {
                    $createData['name'][$lang] = $data['name'];
                }
            }

            // معالجة recipient_name
            if (!empty($data['recipient_name'])) {
                $createData['recipient_name'] = [$locale => trim($data['recipient_name'])];
                try {
                    foreach ($this->otherLangs() as $lang) {
                        try {
                            $createData['recipient_name'][$lang] = $this->autoGoogleTranslator($lang, $data['recipient_name']);
                        } catch (\Exception $e) {
                            Log::warning("Translation failed for recipient_name to $lang: " . $e->getMessage());
                            $createData['recipient_name'][$lang] = $data['recipient_name'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Translation failed for recipient_name: " . $e->getMessage());
                    foreach ($this->otherLangs() as $lang) {
                        $createData['recipient_name'][$lang] = $data['recipient_name'];
                    }
                }
            }

            // معالجة bank_name
            if (!empty($data['bank_name'])) {
                $createData['bank_name'] = [$locale => trim($data['bank_name'])];
                try {
                    foreach ($this->otherLangs() as $lang) {
                        try {
                            $createData['bank_name'][$lang] = $this->autoGoogleTranslator($lang, $data['bank_name']);
                        } catch (\Exception $e) {
                            Log::warning("Translation failed for bank_name to $lang: " . $e->getMessage());
                            $createData['bank_name'][$lang] = $data['bank_name'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Translation failed for bank_name: " . $e->getMessage());
                    foreach ($this->otherLangs() as $lang) {
                        $createData['bank_name'][$lang] = $data['bank_name'];
                    }
                }
            }

            // معالجة instructions
            if (!empty($data['instructions'])) {
                $instructionsText = strip_tags($data['instructions']);
                $instructionsLines = array_filter(array_map('trim', explode("\n", $instructionsText)));

                if (!empty($instructionsLines)) {
                    $createData['instructions'] = [$locale => $instructionsLines];

                    foreach ($this->otherLangs() as $lang) {
                        $translatedLines = [];
                        foreach ($instructionsLines as $line) {
                            if (!empty(trim($line))) {
                                try {
                                    $translatedLines[] = $this->autoGoogleTranslator($lang, $line);
                                } catch (\Exception $e) {
                                    Log::warning("Translation failed for instruction line: " . $e->getMessage());
                                    $translatedLines[] = $line;
                                }
                            }
                        }
                        $createData['instructions'][$lang] = $translatedLines;
                    }
                }
            }

            // حقول بسيطة
            if (isset($data['account_number'])) {
                $createData['account_number'] = $data['account_number'];
            }
            if (isset($data['transfer_number'])) {
                $createData['transfer_number'] = $data['transfer_number'];
            }

            // معالجة العملات
            $currencies = $data['currencies'] ?? [];
            if (is_string($currencies)) {
                $currencies = array_filter(array_map('trim', explode(',', $currencies)));
            }
            if (is_array($currencies)) {
                $currencies = array_values(array_unique(array_filter($currencies)));
            }

            if (empty($currencies)) {
                throw new \Exception('At least one currency is required');
            }

            $createData['currencies'] = [
                'ar' => $this->getArabicCurrencies($currencies),
                'en' => $currencies
            ];
            $createData['currency'] = $currencies[0];

            // إنشاء طريقة الدفع
            $paymentMethod = PaymentMethod::create($createData);

            // رفع الصورة
            if (isset($data['image']) && $data['image']) {
                try {
                    $this->uploadOrUpdateImageWithResize(
                        $paymentMethod,
                        $data['image'],
                        'payment_method_images',
                        'public',
                        false
                    );
                } catch (\Exception $e) {
                    Log::warning("Failed to upload image: " . $e->getMessage());
                    // لا نوقف العملية بسبب فشل رفع الصورة
                }
            }

            DB::commit();

            return $paymentMethod;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment method creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input_data' => $data,
                'store_id' => $store->id ?? null
            ]);
            throw $e;
        }
    }

    /**
     * Update a payment method
     */
    public function update(PaymentMethod $paymentMethod, array $data)
    {
        DB::beginTransaction();

        try {
            $locale = app()->getLocale();
            $updateData = [];

            // معالجة name (مطلوب للتحديث)
            if (isset($data['name']) && !empty($data['name'])) {
                $updateData['name'] = [$locale => trim($data['name'])];
                try {
                    foreach ($this->otherLangs() as $lang) {
                        try {
                            $updateData['name'][$lang] = $this->autoGoogleTranslator($lang, $data['name']);
                        } catch (\Exception $e) {
                            Log::warning("Translation failed for name to $lang: " . $e->getMessage());
                            $updateData['name'][$lang] = $data['name'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Translation failed for name: " . $e->getMessage());
                    foreach ($this->otherLangs() as $lang) {
                        $updateData['name'][$lang] = $data['name'];
                    }
                }
            }

            // معالجة recipient_name
            if (isset($data['recipient_name'])) {
                if (!empty($data['recipient_name'])) {
                    $updateData['recipient_name'] = [$locale => trim($data['recipient_name'])];
                    try {
                        foreach ($this->otherLangs() as $lang) {
                            try {
                                $updateData['recipient_name'][$lang] = $this->autoGoogleTranslator($lang, $data['recipient_name']);
                            } catch (\Exception $e) {
                                Log::warning("Translation failed for recipient_name to $lang: " . $e->getMessage());
                                $updateData['recipient_name'][$lang] = $data['recipient_name'];
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning("Translation failed for recipient_name: " . $e->getMessage());
                        foreach ($this->otherLangs() as $lang) {
                            $updateData['recipient_name'][$lang] = $data['recipient_name'];
                        }
                    }
                } else {
                    $updateData['recipient_name'] = null;
                }
            }

            // معالجة bank_name
            if (isset($data['bank_name'])) {
                if (!empty($data['bank_name'])) {
                    $updateData['bank_name'] = [$locale => trim($data['bank_name'])];
                    try {
                        foreach ($this->otherLangs() as $lang) {
                            try {
                                $updateData['bank_name'][$lang] = $this->autoGoogleTranslator($lang, $data['bank_name']);
                            } catch (\Exception $e) {
                                Log::warning("Translation failed for bank_name to $lang: " . $e->getMessage());
                                $updateData['bank_name'][$lang] = $data['bank_name'];
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning("Translation failed for bank_name: " . $e->getMessage());
                        foreach ($this->otherLangs() as $lang) {
                            $updateData['bank_name'][$lang] = $data['bank_name'];
                        }
                    }
                } else {
                    $updateData['bank_name'] = null;
                }
            }

            // معالجة instructions
            if (isset($data['instructions'])) {
                if (!empty($data['instructions'])) {
                    $instructionsText = strip_tags($data['instructions']);
                    $instructionsLines = array_filter(array_map('trim', explode("\n", $instructionsText)));

                    if (!empty($instructionsLines)) {
                        $updateData['instructions'] = [$locale => $instructionsLines];

                        foreach ($this->otherLangs() as $lang) {
                            $translatedLines = [];
                            foreach ($instructionsLines as $line) {
                                if (!empty(trim($line))) {
                                    try {
                                        $translatedLines[] = $this->autoGoogleTranslator($lang, $line);
                                    } catch (\Exception $e) {
                                        Log::warning("Translation failed for instruction line: " . $e->getMessage());
                                        $translatedLines[] = $line;
                                    }
                                }
                            }
                            $updateData['instructions'][$lang] = $translatedLines;
                        }
                    } else {
                        $updateData['instructions'] = null;
                    }
                } else {
                    $updateData['instructions'] = null;
                }
            }

            // حقول بسيطة
            if (isset($data['account_number'])) {
                $updateData['account_number'] = $data['account_number'];
            }
            if (isset($data['transfer_number'])) {
                $updateData['transfer_number'] = $data['transfer_number'];
            }
            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }

            // معالجة العملات
            if (isset($data['currencies'])) {
                $currencies = $data['currencies'];
                if (is_string($currencies)) {
                    $currencies = array_filter(array_map('trim', explode(',', $currencies)));
                }
                if (is_array($currencies)) {
                    $currencies = array_values(array_unique(array_filter($currencies)));
                }

                if (!empty($currencies)) {
                    $updateData['currencies'] = [
                        'ar' => $this->getArabicCurrencies($currencies),
                        'en' => $currencies
                    ];
                    $updateData['currency'] = $currencies[0];
                }
            }

            $paymentMethod->update($updateData);

            // تحديث الصورة
            if (isset($data['image']) && $data['image']) {
                try {
                    $this->uploadOrUpdateImageWithResize(
                        $paymentMethod,
                        $data['image'],
                        'payment_method_images',
                        'public',
                        true
                    );
                } catch (\Exception $e) {
                    Log::warning("Failed to update image: " . $e->getMessage());
                    // لا نوقف العملية بسبب فشل رفع الصورة
                }
            }

            DB::commit();

            return $paymentMethod;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment method update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input_data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a payment method
     */
    public function delete(PaymentMethod $paymentMethod)
    {
        DB::beginTransaction();

        try {
            $paymentMethod->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment method deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Arabic currency names for English currency codes
     */
    private function getArabicCurrencies($currencyCodes)
    {
        $currencies = [
            'USD' => 'دولار',
            'EUR' => 'يورو',
            'SAR' => 'ريال سعودي',
            'AED' => 'درهم إماراتي',
            'KWD' => 'دينار كويتي',
            'QAR' => 'ريال قطري',
            'BHD' => 'دينار بحريني',
            'OMR' => 'ريال عماني',
            'JOD' => 'دينار أردني',
            'EGP' => 'جنيه مصري',
            'GBP' => 'جنيه إسترليني',
            'JPY' => 'ين ياباني',
            'CNY' => 'يوان صيني',
            'CAD' => 'دولار كندي',
            'AUD' => 'دولار أسترالي',
            'CHF' => 'فرنك سويسري',
            'SEK' => 'كرونة سويدية',
            'NOK' => 'كرونة نرويجية',
            'DKK' => 'كرونة دنماركية',
            'PLN' => 'زلوتي بولندي',
            'CZK' => 'كرونة تشيكية',
            'HUF' => 'فورنت مجري',
            'RUB' => 'روبل روسي',
            'INR' => 'روبية هندية',
            'BRL' => 'ريال برازيلي',
            'MXN' => 'بيزو مكسيكي',
            'ZAR' => 'راند جنوب أفريقي',
            'KRW' => 'وون كوري',
            'SGD' => 'دولار سنغافوري',
            'HKD' => 'دولار هونغ كونغ',
            'NZD' => 'دولار نيوزيلندي',
            'TRY' => 'ليرة تركية',
            'ILS' => 'شيكل إسرائيلي',
            'THB' => 'باهت تايلندي',
            'MYR' => 'رينغيت ماليزي',
            'PHP' => 'بيزو فلبيني',
            'IDR' => 'روبية إندونيسية',
            'VND' => 'دونغ فيتنامي',
            'PKR' => 'روبية باكستانية',
            'BDT' => 'تاكا بنغلاديشي',
            'LKR' => 'روبية سريلانكية',
            'NPR' => 'روبية نيبالية',
            'MMK' => 'كيات ميانماري',
            'KHR' => 'ريال كمبودي',
            'LAK' => 'كيب لاوي',
            'BND' => 'دولار بروني',
            'FJD' => 'دولار فيجي',
            'PGK' => 'كينا بابوا غينيا الجديدة',
            'SBD' => 'دولار جزر سليمان',
            'VUV' => 'فاتو فانواتي',
            'WST' => 'تالا ساموي',
            'TOP' => 'بانغا تونغي',
            'NIO' => 'كوردوبا نيكاراغوي',
            'GTQ' => 'كيتزال غواتيمالي',
            'HNL' => 'لمبيرا هندوراسي',
            'SVC' => 'كولون سلفادوري',
            'BZD' => 'دولار بليزي',
            'JMD' => 'دولار جامايكي',
            'TTD' => 'دولار ترينيدادي',
            'BBD' => 'دولار بربادوسي',
            'XCD' => 'دولار شرق كاريبي',
            'AWG' => 'فلورين أروبي',
            'BMD' => 'دولار برمودي',
            'KYD' => 'دولار كايماني',
            'BSD' => 'دولار باهامي',
            'BWP' => 'بولا بوتسواني',
            'SZL' => 'ليلانغيني سوازيلندي',
            'LSL' => 'لوتي ليسوتو',
            'NAD' => 'دولار ناميبي',
            'MZN' => 'متيكال موزمبيقي',
            'AOA' => 'كوانزا أنغولي',
            'ZMW' => 'كواتشا زامبي',
            'MWK' => 'كواتشا ملاوي',
            'UGX' => 'شلن أوغندي',
            'TZS' => 'شلن تنزاني',
            'KES' => 'شلن كيني',
            'ETB' => 'بير إثيوبي',
            'DJF' => 'فرنك جيبوتي',
            'SOS' => 'شلن صومالي',
            'ERN' => 'ناكفا إريتري',
            'SSP' => 'جنيه جنوب سوداني',
            'SDG' => 'جنيه سوداني',
            'LYD' => 'دينار ليبي',
            'TND' => 'دينار تونسي',
            'DZD' => 'دينار جزائري',
            'MAD' => 'درهم مغربي',
            'MRO' => 'أوقية موريتانية',
            'XOF' => 'فرنك غرب أفريقي',
            'XAF' => 'فرنك وسط أفريقي',
            'GMD' => 'دالاسي غامبي',
            'GHS' => 'سيدي غاني',
            'NGN' => 'نايرا نيجيري',
            'XPF' => 'فرنك بولينيزي',
            'BTC' => 'بتكوين',
            'ETH' => 'إيثريوم',
            'USDT' => 'تيثير',
            'USDC' => 'يو إس دي كوين',
            'BNB' => 'بينانس كوين',
            'ADA' => 'كاردانو',
            'SOL' => 'سولانا',
            'DOT' => 'بولكادوت',
            'MATIC' => 'بوليجون',
            'AVAX' => 'أفالانش',
            'LINK' => 'تشين لينك',
            'UNI' => 'يوني سواب',
            'LTC' => 'لايتكوين',
            'BCH' => 'بتكوين كاش',
            'XRP' => 'ريبل',
            'DOGE' => 'دوجكوين',
            'SHIB' => 'شيبا إينو',
            'TRX' => 'ترون',
            'ATOM' => 'كوزموس',
            'FTM' => 'فانتوم',
            'NEAR' => 'نير بروتوكول',
            'ALGO' => 'ألغوراند',
            'VET' => 'في تشين',
            'ICP' => 'إنترنت كمبيوتر',
            'FIL' => 'فايلكوين',
            'THETA' => 'ثيتا',
            'EOS' => 'إي أو إس',
            'AAVE' => 'أيف',
            'SUSHI' => 'سوشي سواب',
            'COMP' => 'كومباوند',
            'YFI' => 'ييرن فاينانس',
            'SNX' => 'سينثيتيكس',
            'MKR' => 'ميكر',
            'CRV' => 'كرف',
            '1INCH' => 'وان إنش',
            'BAT' => 'بيسيك أتينشن توكين',
            'ZEC' => 'زيكاش',
            'DASH' => 'داش',
            'XMR' => 'مونيرو',
            'NEO' => 'نيو',
            'QTUM' => 'كوانتم',
            'ONT' => 'أونتولوجي',
            'ZIL' => 'زيلينغ',
            'IOTA' => 'أيوتا',
            'NANO' => 'نانو',
            'HBAR' => 'هيديرا',
            'VTHO' => 'في ثور',
            'ICX' => 'آيكون',
            'WAN' => 'وان تشين',
            'ZEN' => 'هوريزن',
            'RVN' => 'ريفين',
            'SC' => 'سيا كوين',
            'DGB' => 'ديجيبايت',
            'XVG' => 'فيرج',
            'DCR' => 'ديكريد',
            'LSK' => 'ليسك',
            'ARK' => 'آرك',
            'WAVES' => 'ويفز',
            'STRAT' => 'ستراتس',
            'KMD' => 'كومودو',
            'PIVX' => 'بيفكس',
            'NAV' => 'ناف كوين',
            'MONA' => 'موناكوين',
            'DENT' => 'دنت',
            'FUN' => 'فان فير',
            'REQ' => 'ريكوست',
            'STORJ' => 'ستورج',
            'GNT' => 'جوليم',
            'REP' => 'أوغور',
            'KNC' => 'كايكو',
            'LRC' => 'لوبين',
            'BNT' => 'بانكور',
            'MANA' => 'ديسينترالاند',
            'LOOM' => 'لوم نتورك',
            'CVC' => 'سيفيك',
            'DNT' => 'دستريكت0إكس',
            'ZRX' => '0إكس',
            'AST' => 'أيراست',
            'POLY' => 'بوليماث',
            'RLC' => 'آي إكس إي سي',
            'GNO' => 'جنوسيس',
            'MLN' => 'ميلون',
            'ADX' => 'أد إكس',
            'ENG' => 'إينيغما',
            'RCN' => 'ريبو كوين',
            'VIB' => 'فايبر',
            'POWR' => 'باور ليدجر'
        ];

        $arabicCurrencies = [];
        foreach ($currencyCodes as $code) {
            $arabicCurrencies[] = $currencies[$code] ?? $code;
        }

        return $arabicCurrencies;
    }
}

