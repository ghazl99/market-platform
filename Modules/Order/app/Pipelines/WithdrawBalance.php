<?php

namespace Modules\Order\Pipelines;

use Closure;

class WithdrawBalance
{
    public function handle($data, Closure $next)
    {
        $wallet = $data['wallet'];
        $old = $wallet->balance;
        $new = $old - $data['total_amount'];

        $wallet->update(['balance' => $new]);

        $data['old_balance'] = $old;
        $data['new_balance'] = $new;

        return $next($data);
    }
}
