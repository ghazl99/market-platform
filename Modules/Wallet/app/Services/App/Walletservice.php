<?php

namespace Modules\Wallet\Services\App;

use Modules\User\Models\User;
use Modules\Store\Models\Store;
use Modules\Wallet\Repositories\App\WalletRepository;

class WalletService
{
    public function __construct(
        protected WalletRepository $walletRepository
    ) {}
    public function getTransactions(array $filters = [], int $perPage = 10)
    {
        return $this->walletRepository->index($filters);
    }
    public function createWalletForUser(User $user, Store $store, float $initialBalance = 0)
    {
        return $this->walletRepository->create([
            'user_id' => $user->id,
            'store_id' => $store->id,
            'balance' => $initialBalance,
        ]);
    }
}
