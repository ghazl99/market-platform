<?php

namespace Modules\Order\Repositories\Dashboard;

use Modules\Order\Models\Order;

class OrderModelRepository implements OrderRepository
{
    public function index(int $storeId)
    {
        return Order::where('store_id', $storeId)
            ->with([
                'items.product',
                'user:id,name,email'
            ])
            ->latest()
            ->paginate(10);
    }

    public function updateStatus(Order $order, array $data): Order
    {
        $order->update($data);

        return $order;
    }
}
