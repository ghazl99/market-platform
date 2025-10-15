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

            $request->validate([
                'name' => 'required|string|max:255',
                'currency' => 'required|string|max:3',
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
                    'ar' => $this->getArabicCurrency($request->currency),
                    'en' => [$request->currency]
                ],
                'currency' => $request->currency,
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
        return view('wallet::dashboard.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'currency' => 'required|string|max:3',
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

            $paymentMethod->update([
                'name' => [
                    'ar' => $request->name,
                    'en' => $request->name
                ],
                'currencies' => [
                    'ar' => $this->getArabicCurrency($request->currency),
                    'en' => [$request->currency]
                ],
                'currency' => $request->currency,
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
                $paymentMethod->clearMediaCollection('payment_method_images');
                $paymentMethod->addMediaFromRequest('image')
                    ->toMediaCollection('payment_method_images');
            }

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
     * Get Arabic currency name for English currency codes
     */
    private function getArabicCurrency($currencyCode)
    {
        $currencies = [
            'USD' => 'دولار',
            'EUR' => 'يورو'
        ];

        return [$currencies[$currencyCode] ?? $currencyCode];
    }
}
