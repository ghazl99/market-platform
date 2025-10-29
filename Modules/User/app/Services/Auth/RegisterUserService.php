<?php

namespace Modules\User\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Pipelines\LoginUser;
use Modules\User\Pipelines\CreateUser;
use Modules\Wallet\Services\App\WalletService;
use Modules\User\Pipelines\AttachStoreAndWallet;
use Modules\User\Pipelines\SendRegisterNotification;
use Modules\User\Repositories\Auth\RegisterUserRepository;

class RegisterUserService
{
    // Inject the user repository
    public function __construct(
        protected RegisterUserRepository $userRepository,
        protected WalletService $walletService

    ) {}
    public function registerCustomer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $result = app(\Illuminate\Pipeline\Pipeline::class)
                ->send($data)
                ->through([
                    CreateUser::class,
                    AttachStoreAndWallet::class,
                    LoginUser::class,
                    SendRegisterNotification::class,
                ])
                ->thenReturn();

            return $result['user'];
        });
    }
    // Register a new user
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $result = app(\Illuminate\Pipeline\Pipeline::class)
                ->send($data)
                ->through([
                    CreateUser::class,
                    // AttachStoreAndWallet::class,
                    LoginUser::class,
                ])
                ->thenReturn();

            return $result['user'];
        });
    }
}
