<?php

namespace Modules\Order\Http\Controllers\App;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Http\Requests\App\StoreOrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\App\OrderService;
use Modules\Store\Models\Store;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    public function store(StoreOrderRequest $request)
    {
        try {
            $result = $this->orderService->store($request->validated());
            // if ($result)
            return redirect()
                ->back()
                ->with('success', __('Created successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ترجع للصفحة مع الرسائل
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }

    // with cart
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $storeId = Store::currentFromUrl()->firstOrFail()->id;

        $filters = $filters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
            'status' => $request->status
        ];
        $orders = $this->orderService->getOrders($userId, $storeId, $filters);
        $statusCounts = [
            'all' => Order::where('user_id', $userId)->where('store_id', $storeId)->count(),
            'pending' => Order::where('user_id', $userId)->where('store_id', $storeId)->where('status', 'pending')->count(),
            'confirmed' => Order::where('user_id', $userId)->where('store_id', $storeId)->where('status', 'confirmed')->count(),
            'completed' => Order::where('user_id', $userId)->where('store_id', $storeId)->where('status', 'completed')->count(),
            'canceled' => Order::where('user_id', $userId)->where('store_id', $storeId)->where('status', 'canceled')->count(),
        ];

        return view('themes.' . current_theme_name_en() . '.orders', compact('orders', 'statusCounts'));
    }

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
    public function show(Order $order)
    {
        $order->load('items.product');

        return view('themes.' . current_theme_name_en() . '.order-details', compact('order'));
    }

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
