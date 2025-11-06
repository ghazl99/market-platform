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
            new Middleware('role:admin|owner', only: ['index', 'update', 'show', 'edit', 'destroy']),

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
        $order->load([
            'items.product.media',
            'user.wallets',
            'store',
            'walletTransactions'
        ]);

        // تحميل محفظة العميل للمتجر الحالي
        if ($order->user && $order->store_id) {
            $order->user->load(['wallets' => function($query) use ($order) {
                $query->where('store_id', $order->store_id);
            }]);
        }

        return view('order::dashboard.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('items.product', 'user');

        return view('order::dashboard.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        logger('Order update method called', [
            'order_id' => $order->id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $request->validate([
            'status' => 'nullable|in:pending,processing,completed,canceled,cancelled',
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        try {
            $data = $request->only('status', 'cancel_reason');

            logger('Data to update', ['data' => $data]);

            $updatedOrder = $this->orderService->updateStatus($order, $data);

            logger('Order updated successfully', [
                'order_id' => $updatedOrder->id,
                'new_status' => $updatedOrder->status,
                'new_cancel_reason' => $updatedOrder->cancel_reason
            ]);

            $message = __('Updated successfully');
            if ($request->status === 'completed') {
                $message = __('Order completed successfully');
            } elseif ($request->status === 'canceled' && $request->cancel_reason) {
                $message = __('Order cancelled successfully');
            }

            // Check if request expects JSON (AJAX) or regular redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return redirect()->route('dashboard.order.show', $order->id)
                ->with('success', $message);
        } catch (\Exception $e) {
            logger()->error('Order update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            // حذف عناصر الطلبية أولاً
            $order->items()->delete();

            // حذف الطلبية
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => __('Order deleted successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
