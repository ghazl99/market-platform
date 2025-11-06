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

        $quantity = (int) $data['quantity'];
        $totalCost = $data['product']->price * $quantity;

        if ($user->debt_limit) {
            $maxDebt = $user->debt_limit;
            $currentDebt = max(0, $totalCost - $wallet->balance);

            if ($currentDebt > $maxDebt) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('Your premium limit exceeded. Maximum allowed debt is :amount $', ['amount' => $maxDebt]));
            }
        } else {
            if ($wallet->balance < $totalCost) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('Your balance is insufficient to complete the purchase.'));
            }
        }

        $data['wallet'] = $wallet;
        $data['total_amount'] = $totalCost;
        $data['quantity'] = $quantity;
        $data['store_id'] = $data['product']->store_id;

        return $next($data);
    }
}
