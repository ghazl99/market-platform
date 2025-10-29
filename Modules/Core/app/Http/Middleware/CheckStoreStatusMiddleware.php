<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Store\Models\Store;

class CheckStoreStatusMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        $store = Store::where('domain', $host)->first();

        if (! $store) {
            $storeDomain = explode('.', $host)[0];
            $store = Store::where('domain', $storeDomain)->first();
        }


        // الحالة غير نشط → ممنوع الدخول
        if ($store->status === 'inactive') {
            abort(403, __('This store is inactive.'));
        }

        // الحالة قيد المراجعة → صفحة تنبيه
        if ($store->status === 'pending') {
            abort(403, __('This store is under review.'));
        }

        return $next($request);
    }
}
