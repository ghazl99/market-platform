<?php

namespace Modules\Cart\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Http\Requests\App\StoreCartItemRequest;
use Modules\Cart\Services\App\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the cart items.
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = $this->cartService->getUserCartItems($userId);

        return view('cart::app.index', compact('cartItems'));
    }

    /**
     * Store a newly created cart item in storage.
     */
    public function store(StoreCartItemRequest $request)
    {

        $validated = $request->validated();

        try {
            $this->cartService->addToCart(
                Auth::id(),
                $validated['product_id'],
                $validated['quantity'],
                $validated['player_id'] ?? null
            );

            return redirect()->route('carts.index')
                ->with('success', __('Created successfully'));
        } catch (\Exception $e) {
            // هنا يمكن تسجيل الخطأ أو التعامل معه

            return redirect()->back()
                ->with('error', 'Failed to add product to cart: '.$e->getMessage());
        }
    }

    /**
     * Display the specified cart item.
     */
    public function show($id)
    {
        $cartItem = $this->cartService->getUserCartItems(Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('cart::show', compact('cartItem'));
    }

    /**
     * Show the form for editing the specified cart item (optional).
     */
    public function edit($id)
    {
        $cartItem = $this->cartService->getUserCartItems(Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('cart::edit', compact('cartItem'));
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $this->cartService->update($id, [
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')
            ->with('success', __('Updated successfully'));
    }

    /**
     * Remove the specified cart item from storage.
     */
    public function destroy($id)
    {
        $this->cartService->deleteItem($id);

        return redirect()->route('cart.index')
            ->with('success', 'Cart item removed successfully');
    }
}
