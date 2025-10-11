<?php

namespace Modules\Order\Pipelines;

use Closure;
use Modules\Wallet\Models\Wallet;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;

class CheckWalletBalance
{
    public function handle($data, Closure $next)
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();
        $product = Product::findOrFail($data['product_id']);

        $quantity = (int) $data['quantity'];
        $totalCost = $product->price * $quantity;

        // إذا المستخدم مميز يسمح له بالدين حتى 1000
        if ($user->is_featured) {
            $maxDebt = 1000; // الحد الأقصى للدين
            $currentDebt = max(0, $totalCost - $wallet->balance);

            if ($currentDebt > $maxDebt) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('Your premium limit exceeded. Maximum allowed debt is :1000 $'));
            }
        } else {
            // مستخدم عادي: الرصيد يجب أن يكون كافي
            if ($wallet->balance < $totalCost) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('Your balance is insufficient to complete the purchase.'));
            }
        }

        // تمرير البيانات للخطوة التالية
        $data['wallet'] = $wallet;
        $data['total_amount'] = $totalCost;
        $data['quantity'] = $quantity;
        $data['store_id'] = $product->store_id;

        return $next($data);
    }
}
