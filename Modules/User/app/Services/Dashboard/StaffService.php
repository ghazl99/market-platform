<?php

namespace Modules\User\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Repositories\Dashboard\StaffRepository;

class StaffService
{
    use \Modules\Core\Traits\ImageTrait;

    public function __construct(
        protected StaffRepository $staffRepository
    ) {}

    public function getStaffs(?string $search = null)
    {
        return $this->staffRepository->index($search);
    }

    public function register(array $data)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }
        // Create user with hashed password
        $user = $this->staffRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->stores()->attach($store->id, ['is_active' => true]);

        // Handle profile photo upload if provided
        if (isset($data['profile_photo'])) {
            $this->uploadOrUpdateImageWithResize(
                $user,
                $data['profile_photo'],
                'profile_photo_images',
                'private_media',
                false
            );
        }

        $user->last_login_at = now();
        $user->save();

        // Return the created user instance
        return $user;
    }

    public function toggleActive(User $user, int $storeId): User
    {
        return $this->staffRepository->toggleActive($user, $storeId);
    }
}
