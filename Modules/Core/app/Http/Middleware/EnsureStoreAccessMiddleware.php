<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Store\Models\Store;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'يجب تسجيل الدخول للوصول.');
        }

        // استخرج اسم المتجر من الساب دومين
        $host = $request->getHost();
        // my-toyes.market-platform.localhost → my-toyes
        $storeDomain = explode('.', $host)[0];

        // ابحث عن المتجر
        $store = Store::where('domain', $storeDomain)->first();

        if (! $store) {
            abort(404, 'المتجر غير موجود.');
        }

        // تحقق من علاقة المستخدم بالمتجر
        $relation = $store->users()
            ->where('user_id', $user->id)
            ->first();

        if (! $relation) {
            abort(403, 'غير مصرح لك بالدخول لهذا المتجر.');
        }

        // تحقق إذا محظور أو غير نشط
        if (! $relation->pivot->is_active) {
            abort(403, 'تم حظرك من هذا المتجر.');
        }

        // مرّر المتجر للراوت عشان تستخدمه بعدين
        $request->attributes->set('store', $store);

        return $next($request);
    }
}
