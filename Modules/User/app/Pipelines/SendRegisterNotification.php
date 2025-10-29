<?php

namespace Modules\User\Pipelines;

use Closure;
use Modules\User\Notifications\RegisterdUser;

class SendRegisterNotification
{
    public function handle($data, Closure $next)
    {
        $data['user']->notify(new RegisterdUser($data['user']));
        return $next($data);
    }
}
