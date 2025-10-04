<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Store\Models\Store;
use Modules\Wallet\Models\PaymentMethod;

class PaymentMethodModelRepository implements PaymentMethodRepository
{
    public function getByStore(Store $store)
    {
        return PaymentMethod::where('store_id', $store->id)
            ->latest()
            ->get();
    }
}
