<?php

namespace Modules\Cart\Repositories\App;

use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartItem;

class CartModelRepository implements CartRepository
{
    // Get existing cart for user or create a new one
    public function getOrCreateUserCart(int $userId)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        // Ensure unique cart for user + store
        return Cart::firstOrCreate(['user_id' => $userId, 'store_id' => $store->id]);
    }

    // Add item to cart or increase quantity if already exists
    public function addItem(int $cartId, $userId, int $productId, int $quantity, ?string $playerId = null)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->firstOrFail();

        // Check if product already exists in user's cart for this store
        if ($store->type === 'digital') {
            $existingItem = CartItem::forUserAndStore($userId, $store->id)
                ->where('product_id', $productId)
                ->where('player_id', $playerId)
                ->first();
        } else {
            $existingItem = CartItem::forUserAndStore($userId, $store->id)
                ->where('product_id', $productId)
                ->first();
        }

        if ($existingItem) {
            // Increase quantity if exists
            $existingItem->quantity += $quantity;
            $existingItem->save();

            return $existingItem;
        }

        // Default item data
        $data = [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ];

        // If store is digital, add extra fields
        if ($store->type === 'digital') {
            $data = array_merge($data, [
                'player_id' => $playerId ?? null,
                // 'delivery_email'  => $digitalData['delivery_email'] ?? null,
                // 'activation_code' => $digitalData['activation_code'] ?? null,
            ]);
        }

        return CartItem::create($data);
    }

    // Get all items in a user's cart for the current store
    public function getUserCartItems(int $userId)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->firstOrFail();

        return CartItem::with(['product'])
            ->forUserAndStore($userId, $store->id)
            ->get();
    }

    // Delete an item from the cart and return remaining count
    public function deleteItem(int $userId, int $storeId): void
    {
        $cart = Cart::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }
    }

    // Update the quantity or digital data of a cart item
    public function update($id, array $data)
    {
        $cartItem = CartItem::findOrFail($id);

        // Fill only allowed fields
        $cartItem->fill($data);
        $cartItem->save();

        return $cartItem;
    }
}
