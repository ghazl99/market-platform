<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Wallet\Models\PaymentRequest;

interface PaymentRequestRepository
{
    public function create(array $data);
    public function findById(int $id): ?PaymentRequest;
    public function update(PaymentRequest $paymentRequest, int $approvedBy);
}
