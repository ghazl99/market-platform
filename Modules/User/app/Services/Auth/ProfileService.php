<?php

namespace Modules\User\Services\Auth;

use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\Auth\ProfileRepository;

class ProfileService
{
    use \Modules\Core\Traits\ImageTrait;

    public function __construct(
        protected ProfileRepository $profileRepository
    ) {}


    public function updateProfile(User $user, array $data): User
    {
        // تحديث البيانات الأساسية
        $this->profileRepository->update($user, $data);

        // تحديث الصورة إذا موجودة
        if (isset($data['profile_photo'])) {
            $this->uploadOrUpdateImageWithResize(
                $user,
                $data['profile_photo'],
                'profile_photo_images',
                'private_media',
                false
            );
        }

        return $user->fresh();
    }

    public function changePassword($user, array $data)
    {
        $user->password = Hash::make($data['password']);
        $user->last_updated_at_password=now();
        $user->save();
    }
}
