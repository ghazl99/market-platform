<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;

interface ProductRepository
{
    public function index(?string $keyword = null);

    public function create(array $data);

    public function find(int $id);

    public function update(int $id, array $data);

    public function delete(Product $product): bool;
}
