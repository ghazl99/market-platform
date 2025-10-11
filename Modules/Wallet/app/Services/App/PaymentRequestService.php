<?php

namespace Modules\Wallet\Services\App;

use Modules\Wallet\Pipelines\AddBalance;
use Modules\Wallet\Pipelines\LogDepositTransaction;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;
use Modules\Wallet\Repositories\App\PaymentRequestRepository;

class PaymentRequestService
{
    use \Modules\Core\Traits\ImageTrait;

    public function __construct(
        protected PaymentRequestRepository $paymentRequestRepository
    ) {}
    public function storeDeposit(array $data)
    {
        // تحويل المبلغ إلى USD
        $amount = $data['amount'];
        $currency = $data['currency'];
        $converted = CurrencyConverter::convert()->from($currency)->to('USD')->amount($amount)->get();
        $exchangeRate = $converted / $amount;

        $depositData = [
            'wallet_id' => $data['wallet_id'],
            'original_amount' => $amount,
            'original_currency' => $currency,
            'amount_usd' => $converted,
            'exchange_rate' => $exchangeRate,
        ];

        // حفظ البيانات
        $deposit = $this->paymentRequestRepository->create($depositData);
        if (! empty($data['receipt_image'])) {
            $this->uploadOrUpdateImageWithResize(
                $deposit,
                $data['receipt_image'],
                'receipt_images',
                'private_media',
                false
            );
        }

        return $deposit;
    }

    public function approvePaymentRequest($paymentRequestId, $authUserId)
    {
        $paymentRequest = $this->paymentRequestRepository->findById($paymentRequestId);

        if (!$paymentRequest || $paymentRequest->status !== 'pending') {
            throw new \Exception('Payment request not found or already processed.');
        }

        // تحديث حالة الطلب
        $this->paymentRequestRepository->update($paymentRequest, $authUserId);

        $wallet = $paymentRequest->wallet;

        // إضافة الرصيد عبر Pipeline
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

        return $paymentRequest;
    }
}
