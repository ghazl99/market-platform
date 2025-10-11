<?php

namespace Modules\User\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\Auth\LoginUserRepository;

class LoginUserService
{
    public function __construct(
        protected LoginUserRepository $userRepository
    ) {}

    public function login(array $data): array
    {
        DB::beginTransaction();
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }
        $user = $this->userRepository->findByEmailAndStore($data['email'], $store->id);

        // Check if email exists
        if (! $user) {
            DB::rollBack();

            return [
                'success' => false,
                'field' => 'email',
                'message' => 'البريد الإلكتروني غير موجود.',
            ];
        }

        // Check password
        if (! Hash::check($data['password'], $user->password)) {
            DB::rollBack();

            return [
                'success' => false,
                'field' => 'password',
                'message' => 'كلمة المرور خاطئة.',
            ];
        }

        // Login user
        Auth::login($user, isset($data['remember']));

        // تحديث وقت آخر تسجيل دخول
        $user->last_login_at = now();
        $user->save();

        request()->session()->regenerate();

        if (!empty($data['fcm_token']) && !empty($data['device_type'])) {
            $this->userRepository->createOrUpdateToken([
                'user_id' => $user->id,
                'token' => $data['fcm_token'],
                'store_id' => $store->id,
                'device_name' => $data['device_type'],
            ]);
        }
        DB::commit();

        // Return success with role info for redirection
        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
