<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Models\Store;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerOfStoreMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // إذا ما مسجل دخول → رجع على صفحة تسجيل الدخول
        if (! $user) {
            return redirect()->route('auth.customer.login')
                ->with('auth', __('You must login as a customer in this store.'));
        }


        // الحالة: دومين مخصص (مثل start-c.com)
        $store = current_store();

        if (! $user->hasRole('customer') || ! $user->stores()->where('store_id', $store->id)->exists()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('auth.customer.login')
                ->with('auth', __('You must login as a customer in this store.'));
        }


        return $next($request);
    }
}
