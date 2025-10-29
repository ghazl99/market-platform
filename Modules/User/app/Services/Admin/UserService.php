<?php

namespace Modules\User\Services\Admin;

use Modules\User\Repositories\Admin\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function getAllUsers($keyword = null, $statusFilter = null, $roleFilter = null)
    {
        return $this->userRepository->index($keyword, $statusFilter, $roleFilter);
    }

    public function deleteUser($id): array
    {
        try {
            $user = $this->userRepository->find($id);
            $this->userRepository->delete($user);

            return ['success' => true, 'message' => 'تم حذف المستخدم بنجاح.'];
        } catch (ModelNotFoundException $e) {
            return ['success' => false, 'message' => 'المستخدم غير موجود.'];
        }
    }
}
