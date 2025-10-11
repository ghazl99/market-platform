<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Wallet\Models\PaymentRequest;

class PaymentRequestModelRepository implements PaymentRequestRepository
{
    public function create(array $data)
    {
        return PaymentRequest::create($data);
    }
    public function findById(int $id): ?PaymentRequest
    {
        return PaymentRequest::find($id);
    }

    public function update(PaymentRequest $paymentRequest, int $approvedBy)
    {
        $paymentRequest->status = 'approved';
        $paymentRequest->approved_by = $approvedBy;
        $paymentRequest->save();

        return $paymentRequest;
    }
}
