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
            return redirect()->route('auth.customer.login');
        }

        // استخرج اسم المتجر من الساب دومين

        $host = $request->getHost(); // مثال: start-c.com أو store.market-platform.localhost
        $mainDomain = config('app.main_domain'); // ضع هنا القيمة مثل 'market-platform.localhost'

        // تحديد ما إذا كان host هو دومين مخصص أو ساب دومين
        if (str_contains($host, $mainDomain)) {
            // الحالة: ساب دومين → extract prefix
            $storeDomain = str_replace('.' . $mainDomain, '', $host);
            $store = Store::where('domain', $storeDomain)->first();
        } else {
            // الحالة: دومين مخصص (مثل start-c.com)
            $store = Store::where('domain', $host)->first();
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
