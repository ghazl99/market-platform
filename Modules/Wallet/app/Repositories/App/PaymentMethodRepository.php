<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Store\Models\Store;

interface PaymentMethodRepository
{
    public function getByStore(Store $store);
}
