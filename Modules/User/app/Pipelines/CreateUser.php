<?php

namespace Modules\User\Pipelines;

use Closure;
use App\Group;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\Auth\RegisterUserRepository;

class CreateUser
{
    public function __construct(
        protected RegisterUserRepository $userRepository,

    ) {}
    public function handle($data, Closure $next)
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'group_id' => Group::getDefaultGroup()?->id,
        ]);

        $data['user'] = $user;

        return $next($data);
    }
}
