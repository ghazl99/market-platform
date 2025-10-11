<?php

namespace Modules\Permission\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = auth()->user();
            $currentAction = $request->route()->getActionName();
            $controllerName = class_basename(Str::beforeLast($currentAction, '@'));
            $methodName = Str::afterLast($currentAction, '@');
            $controllerAndMethod = $controllerName.'@'.$methodName;

            // توليد الصلاحية إذا غير موجودة
            $permission = Permission::firstOrCreate(['name' => $controllerAndMethod]);

            // إذا المستخدم owner → منح الصلاحية تلقائياً
            if ($user->hasRole('owner') && ! $user->hasPermissionTo($permission->name)) {
                $user->givePermissionTo($permission->name);
            }

            // تحقق من الصلاحية
            if (! $user->hasPermissionTo($permission->name)) {
                return abort(403, 'You do not have permission.');
            }
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
            Log::error($e->getMessage());

            return abort(403, 'There is no permission named '.$controllerAndMethod);
        }

        return $next($request);
    }
}
