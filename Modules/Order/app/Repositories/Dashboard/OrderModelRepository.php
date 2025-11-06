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
            ->orderByRaw("CASE
                WHEN status = 'processing' THEN 1
                WHEN status = 'pending' THEN 2
                WHEN status = 'completed' THEN 3
                WHEN status = 'canceled' THEN 4
                WHEN status = 'cancelled' THEN 4
                ELSE 5
            END")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function updateStatus(Order $order, array $data): Order
    {
        $order->update($data);

        return $order;
    }
}
