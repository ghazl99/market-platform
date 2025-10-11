<?php

namespace Modules\Cart\Repositories\App;

interface CartRepository
{
    public function getOrCreateUserCart(int $userId);

    public function addItem(int $cartId, $userId, int $productId, int $quantity, ?string $playerId = null);

    public function getUserCartItems(int $userId);

    public function deleteItem(int $userId, int $storeId): void;

    public function update($id, array $data);
}
