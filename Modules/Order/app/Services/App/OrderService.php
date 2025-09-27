<?php

namespace Modules\Order\Services\App;

use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Illuminate\Validation\ValidationException;
use Modules\Order\Repositories\App\OrderRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected \Modules\Cart\Services\App\CartService $cartService,
    ) {}

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

    public function getOrders(int $userId, int $storeId)
    {
        return $this->orderRepository->index($userId, $storeId);
    }

    public function cancel(Order $order)
    {
        return $this->orderRepository->updateStatus($order, 'canceled');
    }
        */
    public function store(array $data)
    {
        // تحقق من الكمية
        $product = Product::findOrFail($data['product_id']);

        $quantity = (int) $data['quantity'];

        if ($quantity < $product->min_quantity) {
            throw ValidationException::withMessages([
                'quantity' => __('The quantity must be at least :min', ['min' => $product->min_quantity]),
            ]);
        }

        if (!is_null($product->max_quantity) && $quantity > $product->max_quantity) {
            throw ValidationException::withMessages([
                'quantity' => __('The quantity may not be greater than :max', ['max' => $product->max_quantity]),
            ]);
        }

        // إضافة store_id للبيانات
        $data['store_id'] = $product->store_id;

        return $this->orderRepository->createOrder($data);
    }
}
