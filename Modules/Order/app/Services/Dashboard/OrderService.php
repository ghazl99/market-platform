<?php

namespace Modules\Order\Services\Dashboard;

use Modules\Order\Models\Order;
use Modules\Order\Repositories\Dashboard\OrderRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
    ) {}

    public function getOrders(int $storeId)
    {
        return $this->orderRepository->index($storeId);
    }

    public function updateStatus(Order $order, array $data): Order
    {
        return $this->orderRepository->updateStatus($order, $data);
    }
}
