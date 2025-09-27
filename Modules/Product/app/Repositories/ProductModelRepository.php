<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;
use Modules\Store\Models\Store;

class ProductModelRepository implements ProductRepository
{
    public function index(?string $keyword = null)
    {
        $store = Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        if ($keyword) {
            // البحث باستخدام Scout + علاقات categories و attributes
            return Product::search($keyword)
                ->query(function ($query) use ($store, $keyword) {
                    $query->with(['categories', 'attributes', 'store'])
                        ->where('store_id', $store->id)
                        ->orWhereHas('categories', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('attributes', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                })
                ->paginate(20);
        }

        return Product::with(['categories', 'attributes', 'store'])
            ->where('store_id', $store->id)
            ->paginate(20);
    }

    public function create(array $data)
    {
        // إنشاء المنتج
        $product = Product::create($data);

        // ربط الأقسام
        $product->categories()->sync($data['categories']);

        return $product;
    }

    public function find(int $id)
    {
        return Product::with(['categories', 'attributes'])->find($id);
    }

    public function update(int $id, array $data)
    {
        $product = Product::find($id);
        if (! $product) {
            abort(404);
        }

        $product->update($data);

        if (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
