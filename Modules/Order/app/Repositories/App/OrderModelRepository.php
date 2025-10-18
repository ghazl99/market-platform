<?php

namespace Modules\Order\Repositories\App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

class OrderModelRepository implements OrderRepository
{
    public function index(int $userId, int $storeId, array $filters = [])
    {
        $query = Order::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->with('items.product');

        // فلترة حسب التاريخ
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('items.product', function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        return $query->latest()->paginate(10);
    }

    /*
    with cart


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
            // إنشاء الطلب مع المبلغ الإجمالي المحسوب
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'store_id' => $data['store_id'],
                'status' => 'pending',
                'payment_status' => 'paid',
                'total_amount' => $data['total_amount'], // حفظ المبلغ المحسوب
            ]);

            // إضافة المنتج للطلب
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'player_id' => $data['player_id'] ?? null,
                'delivery_email' => $data['delivery_email'] ?? null,
            ]);

            return $order;
        });
    }
}
