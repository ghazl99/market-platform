<?php

namespace Modules\User\Observers;

use Modules\User\Models\User;
use Modules\Wallet\Services\App\WalletService;

class UserObserver
{
    public function __construct(
        protected WalletService $walletService
    ) {}
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        // فقط نفذ إنشاء المحفظة والإشعارات إذا كان هناك متجر
        if ($store) {
            if ($user->hasRole('customer|staff')) {
                $this->walletService->createWalletForUser($user, $store, 0);
            }

            if ($user->hasRole('customer')) {
                $user->notify(new \Modules\User\Notifications\RegisterdUser($user));
            }
        }
    }


    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void {}

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void {}

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void {}

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void {}
}
