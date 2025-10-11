<?php

namespace Modules\Order\Pipelines;

use Closure;
use Modules\Order\Repositories\App\OrderRepository;

class CreateOrder
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle($data, Closure $next)
    {
        // أنشئ الطلب عن طريق المستودع
        $order = $this->orderRepository->createOrder($data);

        $data['order_id'] = $order->id;

        return $next($data);
    }
}
