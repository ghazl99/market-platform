<?php

namespace Modules\Order\Services\App;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Pipelines\CreateOrder;
use Modules\Order\Pipelines\LogTransaction;
use Modules\Order\Pipelines\WithdrawBalance;
use Illuminate\Validation\ValidationException;
use Modules\Order\Pipelines\CheckWalletBalance;
use Modules\Order\Pipelines\CheckQuantityLimits;
use Modules\Order\Repositories\App\OrderRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected \Modules\Cart\Services\App\CartService $cartService,
    ) {}

    public function getOrders($userId, $storeId, $filters)
    {
        return $this->orderRepository->index($userId, $storeId, $filters);
    }

    /* with cart
    public function create(int $userId, int $storeId, $cartItems)
    {
        $orderData = [
            'user_id' => $userId,
            'store_id' => $storeId,
        ];

        $order = $this->orderRepository->store($orderData, $cartItems);
        $this->cartService->clearUserCart($userId, $storeId);

        return $order;
    }



    public function cancel(Order $order)
    {
        return $this->orderRepository->updateStatus($order, 'canceled');
    }
        */
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            return app(\Illuminate\Pipeline\Pipeline::class)
                ->send($data)
                ->through([
                    CheckWalletBalance::class,
                    CheckQuantityLimits::class,
                    CreateOrder::class,
                    WithdrawBalance::class,
                    LogTransaction::class,
                ])
                ->thenReturn();
                
        });
    }
}
