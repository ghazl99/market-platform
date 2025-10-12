<?php

namespace Modules\Wallet\Services\Dashboard;

use Modules\Wallet\Pipelines\AddBalance;
use Modules\Wallet\Pipelines\LogDepositTransaction;

class PaymentRequestService
{

    public function __construct(
        protected \Modules\Wallet\Repositories\Dashboard\PaymentRequestRepository $paymentRequestRepository
    ) {}

    public function processPaymentRequest(int $paymentRequestId, int $authUserId, array $validated)
    {
        $paymentRequest = $this->paymentRequestRepository->findById($paymentRequestId);
        if (!$paymentRequest || $paymentRequest->status !== 'pending') {
            throw new \Exception('Payment request not found or already processed.');
        }

        // تحديث حالة الطلب والملاحظة
        $this->paymentRequestRepository->updateStatusAndNotes($paymentRequest, $authUserId, $validated);

        if ($validated['status'] === 'approved') {
            $wallet = $paymentRequest->wallet;

            $data = [
                'wallet' => $wallet,
                'amount_usd' => $paymentRequest->amount_usd,
                'paymentRequest_id' => $paymentRequestId,
            ];

            app(\Illuminate\Pipeline\Pipeline::class)
                ->send($data)
                ->through([
                    AddBalance::class,
                    LogDepositTransaction::class,
                ])
                ->thenReturn();
        }

        return $paymentRequest;
    }
}
