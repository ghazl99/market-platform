<?php

namespace Modules\Wallet\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Modules\Wallet\Models\PaymentMethod;
use Modules\Wallet\Services\App\PaymentMethodService;

class PaymentMethodController extends Controller
{
    public function __construct(
        protected PaymentMethodService $paymentMethodService
    ) {}

    /**
     * Display a listing of payment methods for dashboard
     */
    public function index()
    {
        $store = Store::currentFromUrl()->firstOrFail();
        $paymentMethods = $this->paymentMethodService->getForCurrentStore($store);

        return view('wallet::dashboard.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method
     */
    public function create()
    {
        return view('wallet::dashboard.payment-methods.create');
    }

    /**
     * Store a newly created payment method
     */
    public function store(Request $request)
    {
        try {
            $store = Store::currentFromUrl()->firstOrFail();

            // Normalize currencies input to an array
            $currencies = $request->input('currencies', []);
            if (is_string($currencies)) {
                $currencies = array_filter(array_map('trim', explode(',', $currencies)));
            }
            if (empty($currencies)) {
                // Try collect from repeated inputs currencies[] if present
                $currencies = (array) $request->input('currencies', []);
            }
            // Ensure unique, reindex
            if (is_array($currencies)) {
                $currencies = array_values(array_unique(array_filter($currencies)));
            }
            // Merge back for validation
            $request->merge(['currencies' => $currencies]);

            $request->validate([
                'name' => 'required|string|max:255',
                'currencies' => 'required|array|min:1',
                'currencies.*' => 'string|max:10',
                'recipient_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'transfer_number' => 'nullable|string|max:255',
                'instructions' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Convert instructions string to array format for storage
            $instructionsArray = [];
            if ($request->instructions) {
                $instructionsText = strip_tags($request->instructions);
                $instructionsLines = array_filter(array_map('trim', explode("\n", $instructionsText)));
                $instructionsArray = [
                    'ar' => $instructionsLines,
                    'en' => $instructionsLines
                ];
            }

            $paymentMethod = PaymentMethod::create([
                'store_id' => $store->id,
                'name' => [
                    'ar' => $request->name,
                    'en' => $request->name
                ],
                'currencies' => [
                    'ar' => $this->getArabicCurrencies($request->currencies),
                    'en' => $request->currencies
                ],
                'currency' => $request->currencies[0], // حفظ العملة الأولى كعملة افتراضية
                'recipient_name' => $request->recipient_name ? [
                    'ar' => $request->recipient_name,
                    'en' => $request->recipient_name
                ] : null,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name ? [
                    'ar' => $request->bank_name,
                    'en' => $request->bank_name
                ] : null,
                'transfer_number' => $request->transfer_number,
                'instructions' => $instructionsArray,
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->hasFile('image')) {
                $paymentMethod->addMediaFromRequest('image')
                    ->toMediaCollection('payment_method_images');
            }

            return redirect()->route('dashboard.dashboard.payment-methods.index')
                ->with('success', __('Payment method created successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to create payment method. Please try again.'));
        }
    }

    /**
     * Show the form for editing the specified payment method
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        // تحويل البيانات القديمة إذا كانت سلسلة نصية
        $currencies = $paymentMethod->currencies;
        if (is_string($currencies)) {
            $currencies = json_decode($currencies, true);
        }

        // التأكد من أن العملات مصفوفة وإزالة التكرار
        if (isset($currencies['en']) && is_string($currencies['en'])) {
            $currencies['en'] = explode(',', $currencies['en']);
            $currencies['en'] = array_map('trim', $currencies['en']);
            $currencies['en'] = array_filter($currencies['en']); // إزالة القيم الفارغة
            $currencies['en'] = array_unique($currencies['en']); // إزالة التكرار
            $currencies['en'] = array_values($currencies['en']); // إعادة ترقيم المفاتيح
        }

        if (isset($currencies['ar']) && is_string($currencies['ar'])) {
            $currencies['ar'] = explode(',', $currencies['ar']);
            $currencies['ar'] = array_map('trim', $currencies['ar']);
            $currencies['ar'] = array_filter($currencies['ar']); // إزالة القيم الفارغة
            $currencies['ar'] = array_unique($currencies['ar']); // إزالة التكرار
            $currencies['ar'] = array_values($currencies['ar']); // إعادة ترقيم المفاتيح
        }

        // إزالة التكرار من المصفوفات أيضاً
        if (isset($currencies['en']) && is_array($currencies['en'])) {
            $currencies['en'] = array_unique($currencies['en']);
            $currencies['en'] = array_values($currencies['en']);
        }

        if (isset($currencies['ar']) && is_array($currencies['ar'])) {
            $currencies['ar'] = array_unique($currencies['ar']);
            $currencies['ar'] = array_values($currencies['ar']);
        }

        // إعادة تعيين العملات المنظفة
        $paymentMethod->setAttribute('currencies', $currencies);

        return view('wallet::dashboard.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            // تسجيل البيانات المرسلة للتشخيص
            logger()->info('Payment Method Update Request:', [
                'payment_method_id' => $paymentMethod->id,
                'request_data' => $request->all(),
                'currencies' => $request->currencies ?? 'not_provided',
                'currencies_raw' => $request->input('currencies'),
                'all_inputs' => $request->input()
            ]);

            // التحقق من وجود العملات ومعالجة البيانات
            $currencies = $request->input('currencies', []);

            // إذا كانت العملات سلسلة نصية أو فارغة، جرب الحقل الاحتياطي
            if (is_string($currencies) || empty($currencies)) {
                $backupCurrencies = $request->input('currencies_backup', '');
                if (!empty($backupCurrencies)) {
                    $currencies = explode(',', $backupCurrencies);
                    $currencies = array_map('trim', $currencies);
                    $currencies = array_filter($currencies);
                } elseif (is_string($currencies)) {
                    $currencies = explode(',', $currencies);
                    $currencies = array_map('trim', $currencies);
                    $currencies = array_filter($currencies);
                }
            }

            if (empty($currencies)) {
                logger()->error('No currencies provided in request');
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Please select at least one currency.'));
            }

            logger()->info('Processed currencies:', $currencies);

            // إضافة العملات المُعالجة إلى الطلب للتحقق
            $request->merge(['currencies' => $currencies]);

            $request->validate([
                'name' => 'required|string|max:255',
                'currencies' => 'required|array|min:1',
                'currencies.*' => 'string|max:3',
                'recipient_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'transfer_number' => 'nullable|string|max:255',
                'instructions' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            logger()->info('Validation passed, currencies:', $request->currencies);

            // Convert instructions string to array format for storage
            $instructionsArray = [];
            if ($request->instructions) {
                $instructionsText = strip_tags($request->instructions);
                $instructionsLines = array_filter(array_map('trim', explode("\n", $instructionsText)));
                $instructionsArray = [
                    'ar' => $instructionsLines,
                    'en' => $instructionsLines
                ];
            }

            $updateData = [
                'name' => [
                    'ar' => $request->name,
                    'en' => $request->name
                ],
                'currencies' => [
                    'ar' => $this->getArabicCurrencies($currencies),
                    'en' => $currencies
                ],
                'currency' => $currencies[0], // حفظ العملة الأولى كعملة افتراضية
                'recipient_name' => $request->recipient_name ? [
                    'ar' => $request->recipient_name,
                    'en' => $request->recipient_name
                ] : null,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name ? [
                    'ar' => $request->bank_name,
                    'en' => $request->bank_name
                ] : null,
                'transfer_number' => $request->transfer_number,
                'instructions' => $instructionsArray,
                'is_active' => $request->boolean('is_active', true),
            ];

            logger()->info('Update data prepared:', $updateData);

            $result = $paymentMethod->update($updateData);

            logger()->info('Update result:', ['success' => $result]);

            if ($request->hasFile('image')) {
                $paymentMethod->clearMediaCollection('payment_method_images');
                $paymentMethod->addMediaFromRequest('image')
                    ->toMediaCollection('payment_method_images');
            }

            return redirect()->route('dashboard.dashboard.payment-methods.index')
                ->with('success', __('Payment method updated successfully'));
        } catch (\Exception $e) {
            Logger()->error('Payment Method Update Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to update payment method. Please try again.'));
        }
    }

    /**
     * Remove the specified payment method
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->delete();

            return redirect()->route('dashboard.dashboard.payment-methods.index')
                ->with('success', __('Payment method deleted successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('Failed to delete payment method. Please try again.'));
        }
    }

    /**
     * Toggle payment method status
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $paymentMethod->is_active,
            'message' => $paymentMethod->is_active
                ? __('Payment method activated')
                : __('Payment method deactivated')
        ]);
    }

    /**
     * Get English name for Arabic payment method names
     */
    private function getEnglishName($arabicName)
    {
        $names = [
            'بينانس' => 'Binance',
            'بايبال' => 'PayPal',
            'USDT TRC20' => 'USDT TRC20'
        ];

        return $names[$arabicName] ?? $arabicName;
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
