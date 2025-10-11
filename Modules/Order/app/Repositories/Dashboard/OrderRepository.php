<?php

namespace Modules\Order\Repositories\Dashboard;

use Modules\Order\Models\Order;

interface OrderRepository
{
    public function index(int $storeId);

    public function updateStatus(Order $order, array $data): Order;
}
