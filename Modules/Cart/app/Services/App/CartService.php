<?php

namespace Modules\Cart\Services\App;

use Modules\Cart\Repositories\App\CartRepository;

class CartService
{
    // Inject the CartRepository
    public function __construct(
        protected CartRepository $cartRepository,
    ) {}

    // Add a product to the user's cart
    public function addToCart(
        int $userId,
        int $productId,
        int $quantity,
        ?string $playerId = null
    ) {
        // Get existing cart or create a new one
        $cart = $this->cartRepository->getOrCreateUserCart($userId);

        // Add the item to the cart
        return $this->cartRepository->addItem($cart->id, $userId, $productId, $quantity, $playerId);
    }

    // Get all items in a user's cart
    public function getUserCartItems(int $userId)
    {
        return $this->cartRepository->getUserCartItems($userId);
    }

    // Delete a cart item and return remaining count
    public function clearUserCart(int $userId, int $storeId): void
    {
        $this->cartRepository->deleteItem($userId, $storeId);
    }

    // Update quantity or digital data of a cart item
    public function update($id, array $data)
    {
        return $this->cartRepository->update($id, $data);
    }
}
