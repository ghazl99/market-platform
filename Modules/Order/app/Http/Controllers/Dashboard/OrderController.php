<?php

namespace Modules\Order\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Order\Models\Order;
use Modules\Order\Services\Dashboard\OrderService;
use Modules\Store\Models\Store;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index', 'update', 'show']),

        ];
    }

    public function __construct(
        protected OrderService $orderService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storeId = Store::currentFromUrl()->firstOrFail()->id;

        $orders = $this->orderService->getOrders($storeId);

        return view('order::dashboard.index', compact('orders'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.product');

        return view('order::dashboard.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,completed,canceled',
        ]);

        try {
            $data = $request->only('status');
            $this->orderService->updateStatus($order, $data);

            return response()->json([
                'success' => true,
                'message' => __('Updated successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
