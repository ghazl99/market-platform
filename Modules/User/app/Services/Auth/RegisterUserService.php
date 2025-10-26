<?php

namespace Modules\User\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Services\Admin\HomeService;
use Modules\User\Repositories\Auth\RegisterUserRepository;

class RegisterUserService
{
    // Inject the user repository
    public function __construct(
        protected RegisterUserRepository $userRepository,
        protected HomeService $homeService
    ) {}
    public function registerCustomer(array $data)
    {

        return DB::transaction(function () use ($data) {

            // Create user with hashed password
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
                'group_id' => \App\Group::getDefaultGroup()?->id,

            ]);

            $store = current_store();

            if (! $store) {
                abort(404, 'Store not found');
            }
            if (! $user->stores()->where('store_id', $store->id)->exists()) {
                $user->stores()->attach($store->id, ['is_active' => true]);
            }

            // Log the user in after successful registration
            Auth::login($user);
            $user->last_login_at = now();
            $user->save();

            // Return the created user instance
            return $user;
        });
    }
    // Register a new user
    public function register(array $data)
    {

        return DB::transaction(function () use ($data) {

            // Create user with hashed password
            $user = $this->userRepository->createOwner([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
            ]);

            // Log the user in after successful registration
            Auth::login($user);
            $user->last_login_at = now();
            $user->save();

            // Return the created user instance
            return $user;
        });
    }
}
