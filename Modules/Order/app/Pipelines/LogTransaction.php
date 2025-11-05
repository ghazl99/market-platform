<?php

namespace Modules\Order\Pipelines;

use Closure;

class LogTransaction
{
    public function handle($data, Closure $next)
    {
        \Modules\Wallet\Models\WalletTransaction::insert([
            'wallet_id' => $data['wallet']->id,
            'order_id' => $data['order_id'] ?? null,
            'type' => 'withdraw',
            'old_balance' => $data['old_balance'] ?? null,
            'new_balance' => $data['new_balance'] ?? null,
            'amount' => $data['total_amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $next($data);
    }
}
