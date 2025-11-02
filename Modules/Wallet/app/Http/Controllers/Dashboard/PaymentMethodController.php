<?php

namespace Modules\Wallet\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Modules\Wallet\Models\PaymentMethod;
use Modules\Wallet\Services\Dashboard\PaymentMethodService;

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
        // الحصول على المتجر من middleware أو من URL
        $store = request()->attributes->get('store') ?? Store::currentFromUrl()->firstOrFail();
        $paymentMethods = $this->paymentMethodService->getForStore($store);

        return view('wallet::dashboard.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method
     */
    public function create()
    {
        // التحقق من وجود المتجر (للتأكد من أن middleware يعمل)
        try {
            $store = request()->attributes->get('store') ?? Store::currentFromUrl()->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Payment Method Create - Store not found: ' . $e->getMessage());
            abort(404, __('Store not found. Please make sure you are accessing from the correct URL.'));
        }

        return view('wallet::dashboard.payment-methods.create');
    }

    /**
     * Store a newly created payment method
     */
    public function store(Request $request)
    {
        try {
            // الحصول على المتجر من middleware أو من URL
            $store = $request->attributes->get('store') ?? Store::currentFromUrl()->firstOrFail();

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

            // تحضير البيانات بشكل صريح
            $data = [];
            $data['name'] = $request->input('name');
            $data['recipient_name'] = $request->input('recipient_name');
            $data['bank_name'] = $request->input('bank_name');
            $data['account_number'] = $request->input('account_number');
            $data['transfer_number'] = $request->input('transfer_number');
            $data['instructions'] = $request->input('instructions');
            $data['currencies'] = $currencies;
            $data['image'] = $request->file('image');
            $data['is_active'] = $request->boolean('is_active', true);

            $paymentMethod = $this->paymentMethodService->create($data, $store);

            return redirect()->route('dashboard.dashboard.payment-methods.index')
                ->with('success', __('Payment method created successfully'));
        } catch (\Exception $e) {
            Log::error('Payment Method Creation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['image'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to create payment method: ') . $e->getMessage());
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
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Please select at least one currency.'));
            }

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

            // تحضير البيانات
            $data = $request->only([
                'name',
                'recipient_name',
                'bank_name',
                'account_number',
                'transfer_number',
                'instructions',
                'is_active'
            ]);

            $data['currencies'] = $currencies;
            $data['image'] = $request->file('image');
            $data['is_active'] = $request->boolean('is_active', true);

            $this->paymentMethodService->update($paymentMethod, $data);

            return redirect()->route('dashboard.dashboard.payment-methods.index')
                ->with('success', __('Payment method updated successfully'));
        } catch (\Exception $e) {
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
            $this->paymentMethodService->delete($paymentMethod);

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
}
