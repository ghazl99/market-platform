<?php

namespace Modules\Order\Pipelines;

use Closure;

class WithdrawBalance
{
    public function handle($data, Closure $next)
    {
        $wallet = $data['wallet'];
        $data['old_balance'] = $wallet->balance;

        $wallet->balance -= $data['total_amount'];
        $data['new_balance'] = $wallet->balance;
        
        $wallet->save();

        $data['wallet'] = $wallet;

        return $next($data);
    }
}
