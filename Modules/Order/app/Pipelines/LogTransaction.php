<?php

namespace Modules\Order\Pipelines;

use Closure;
use Modules\Wallet\Models\WalletTransaction;

class LogTransaction
{
    public function handle($data, Closure $next)
    {
        WalletTransaction::create([
            'wallet_id' => $data['wallet']->id,
            'order_id' => $data['order_id'] ?? null,
            'type' => 'withdraw',
            'old_balance' => $data['old_balance'] ?? null,
            'new_balance' => $data['new_balance'] ?? null,
            'amount' => $data['total_amount'],

        ]);

        return $next($data);
    }
}
