<?php

namespace Modules\Wallet\Services\App;

use Modules\Store\Models\Store;
use Modules\Wallet\Repositories\App\PaymentMethodRepository;

class PaymentMethodService
{
    public function __construct(
        protected PaymentMethodRepository $paymentMethodRepository
    ) {}

    public function getForCurrentStore(Store $store)
    {
        return $this->paymentMethodRepository->getByStore($store);
    }
}
