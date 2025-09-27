<?php

namespace Modules\Order\Repositories\App;

use Modules\Order\Models\Order;

interface OrderRepository
{
    /*
    with cart
    public function index(int $userId, int $storeId);

    public function store(array $orderData, $items);

    public function updateStatus(Order $order, string $status);
   */

    //without cart
    public function createOrder(array $data): Order;
}
