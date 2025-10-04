<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Wallet\Models\PaymentRequest;

class PaymentRequestModelRepository implements PaymentRequestRepository
{
    public function create(array $data)
    {
        return PaymentRequest::create($data);
    }
}
