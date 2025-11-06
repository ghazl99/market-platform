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
    public function getAllForCurrentStore(int $storeId)
    {
        return $this->paymentRequestRepository->getAllForCurrentStore($storeId);
    }
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
            'payment_method_id' => $data['payment_method_id'],
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
}
