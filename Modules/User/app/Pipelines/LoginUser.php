<?php

namespace Modules\User\Pipelines;

use Closure;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    public function handle($data, Closure $next)
    {
        $user = $data['user'];
        Auth::login($user);

        $user->last_login_at = now();
        $user->save();

        return $next($data);
    }
}
