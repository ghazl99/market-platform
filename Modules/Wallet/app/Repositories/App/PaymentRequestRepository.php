<?php

namespace Modules\Wallet\Repositories\App;

interface PaymentRequestRepository
{
    public function create(array $data);
}
