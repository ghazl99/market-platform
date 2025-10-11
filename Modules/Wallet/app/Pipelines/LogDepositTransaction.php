<?php

namespace Modules\Wallet\Pipelines;

use Closure;
use Modules\Wallet\Models\WalletTransaction;

class LogDepositTransaction
{
    public function handle($data, Closure $next)
    {
        WalletTransaction::create([
            'wallet_id' => $data['wallet']->id,
            'paymentRequest_id' => $data['paymentRequest_id'] ?? null,
            'type' => 'deposit',
            'old_balance' => $data['old_balance'] ?? null,
            'new_balance' => $data['new_balance'] ?? null,
            'amount' => $data['amount_usd'],
        ]);

        return $next($data);
    }
}
