<?php

namespace Modules\Order\Http\Controllers\App;

use Illuminate\Http\Request;
use Modules\Order\Models\Order;
use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Services\App\OrderService;
use Modules\Order\Http\Requests\App\StoreOrderRequest;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}


    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->store($request->validated());

            return redirect()
                ->route('order.show', $order->id)
                ->with('success', __('Created successfully'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    //with cart
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $userId = Auth::id();
    //     $storeId = Store::currentFromUrl()->firstOrFail()->id;

    //     $orders = $this->orderService->getOrders($userId, $storeId);

    //     return view('order::app.index', compact('orders'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('order::create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $userId = Auth::id();
    //     $storeId = Store::currentFromUrl()->firstOrFail()->id;
    //     $cart = \Modules\Cart\Models\Cart::where('user_id', $userId)
    //         ->where('store_id', $storeId)
    //         ->with('items.product')
    //         ->first();

    //     if (! $cart || $cart->items->isEmpty()) {
    //         return redirect()->back()->with('error', __('Cart is empty'));
    //     }

    //     $order = $this->orderService->create($userId, $storeId, $cart->items);

    //     return redirect()->route('order.show', $order->id)
    //         ->with('success', __('Created successfully'));
    // }

    /**
     * Show the specified resource.
     */
    // public function show(Order $order)
    // {
    //     $order->load('items.product');

    //     return view('order::app.show', compact('order'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     return view('order::edit');
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Order $order)
    // {
    //     if ($request->input('action') === 'canceled') {
    //         $this->orderService->cancel($order);

    //         if ($request->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => __('Updated successfully'),
    //             ]);
    //         }

    //         return redirect()->back()->with('success', __('Updated successfully'));
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id) {}
}
