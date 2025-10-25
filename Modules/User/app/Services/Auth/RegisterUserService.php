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

    // Register a new user
    public function register(array $data, $host)
    {
        $mainDomain = app()->environment('production')
            ? config('app.main_domain', 'soqsyria.com')
            : 'market-platform.localhost';
        return DB::transaction(function () use ($data, $host, $mainDomain) {

            // Create user with hashed password
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
                'group_id' => \App\Group::getDefaultGroup()?->id,
            ]);
            if (! $this->homeService->isMainDomain($host, $mainDomain)) {
                $store = \Modules\Store\Models\Store::currentFromUrl()->first();

                if (! $store) {
                    abort(404, 'Store not found');
                }
                if (! $user->stores()->where('store_id', $store->id)->exists()) {
                    $user->stores()->attach($store->id, ['is_active' => true]);
                }
            }

            // Log the user in after successful registration
            Auth::login($user);
            $user->last_login_at = now();
            $user->save();

            // Return the created user instance
            return $user;
        });
    }
}
