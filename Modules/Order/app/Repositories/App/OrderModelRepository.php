<?php

namespace Modules\Order\Repositories\App;

use Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderModelRepository implements OrderRepository
{
    /*
    with cart
    *public function index(int $userId, int $storeId)
    *{
        return Order::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->with('items.product') // تجيب المنتجات
            ->latest()
            ->paginate(10);
    *}

    *public function store(array $orderData, $items)
   * {
        return DB::transaction(function () use ($orderData, $items) {
            $order = Order::create($orderData);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'player_id' => $item->player_id ?? null,
                    //     'delivery_email'   => $item->delivery_email,
                    //     'activation_code'  => $item->activation_code,
                ]);
            }

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status)
    {
        $order->status = $status;
        $order->save();

        return $order;
    }
        */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // إنشاء الطلب
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'store_id' => $data['store_id'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // إضافة المنتج للطلب
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $data['product_id'],
                'quantity'   => $data['quantity'],
                'player_id'  => $data['player_id'] ?? null,
                'delivery_email' => $data['delivery_email'] ?? null,
            ]);

            return $order;
        });
    }
}
