<?php

namespace Modules\Order\Pipelines;

use Closure;
use Modules\Product\Models\Product;
use Illuminate\Validation\ValidationException;

class CheckQuantityLimits
{
    public function handle($data, Closure $next)
    {
        if ($data['quantity'] < $data['product']->min_quantity) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('The quantity must be at least :min', ['min' => $data['product']->min_quantity]));
        }

        if (! is_null($data['product']->max_quantity) && $data['quantity'] > $data['product']->max_quantity) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('The quantity may not be greater than :max', ['max' => $data['product']->max_quantity]));
        }
        $data['product']->increment('orders_count');
        return $next($data);
    }
}
