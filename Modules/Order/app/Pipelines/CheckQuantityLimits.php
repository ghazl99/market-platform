<?php

namespace Modules\Order\Pipelines;

use Closure;
use Modules\Product\Models\Product;
use Illuminate\Validation\ValidationException;

class CheckQuantityLimits
{
    public function handle($data, Closure $next)
    {
            $product = Product::findOrFail($data['product_id']);
            if ($data['quantity'] < $product->min_quantity) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('The quantity must be at least :min', ['min' => $product->min_quantity]));
            }

            if (! is_null($product->max_quantity) && $data['quantity'] > $product->max_quantity) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('The quantity may not be greater than :max', ['max' => $product->max_quantity]));
            }
            $product->increment('orders_count');
        return $next($data);
    }
}
