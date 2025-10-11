<?php

namespace Modules\Wallet\Pipelines;

use Closure;

class AddBalance
{
    public function handle($data, Closure $next)
    {
        $wallet = $data['wallet'];
        $data['old_balance'] = $wallet->balance;

        // إضافة الرصيد
        $wallet->balance += $data['amount_usd'];
        $data['new_balance'] = $wallet->balance;

        $wallet->save();

        $data['wallet'] = $wallet;

        return $next($data);
    }
}
