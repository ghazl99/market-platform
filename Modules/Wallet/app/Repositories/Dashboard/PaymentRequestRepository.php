<?php

namespace Modules\Wallet\Repositories\Dashboard;

use Modules\Wallet\Models\PaymentRequest;

interface PaymentRequestRepository
{
        public function findById(int $id): ?PaymentRequest;
    public function updateStatusAndNotes(PaymentRequest $paymentRequest, int $approvedBy, array $validated);
}
