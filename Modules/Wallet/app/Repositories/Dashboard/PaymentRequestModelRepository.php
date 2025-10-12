<?php

namespace Modules\Wallet\Repositories\Dashboard;

use Modules\Wallet\Models\PaymentRequest;

class PaymentRequestModelRepository implements PaymentRequestRepository
{

    public function findById(int $id): ?PaymentRequest
    {
        return PaymentRequest::find($id);
    }
    public function updateStatusAndNotes(PaymentRequest $paymentRequest, int $approvedBy, array $validated)
    {
        $paymentRequest->status = $validated['status'];
        $paymentRequest->approved_by = $approvedBy;

        if (!empty($validated['notes'])) {
            $paymentRequest->notes = $validated['notes'];
        }

        $paymentRequest->save();

        return $paymentRequest;
    }
}
