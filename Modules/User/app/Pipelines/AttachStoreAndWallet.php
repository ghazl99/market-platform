<?php

namespace Modules\User\Pipelines;

use Closure;
use Modules\Wallet\Services\App\WalletService;

class AttachStoreAndWallet
{

    public function __construct(
        protected WalletService $walletService

    ) {}

    public function handle($data, Closure $next)
    {
        $user = $data['user'];
        $store = current_store();

        if (! $store) {
            abort(404, 'Store not found');
        }

        if (! $user->stores()->where('store_id', $store->id)->exists()) {
            $user->stores()->attach($store->id, ['is_active' => true]);
            $this->walletService->createWalletForUser($user, $store, 0);
        }

        return $next($data);
    }
}
