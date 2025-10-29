<?php

namespace Modules\Product\Repositories;

use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;

class ProductModelRepository implements ProductRepository
{
    public function index(?string $keyword = null, ?int $categoryFilter = null, ?string $statusFilter = null)
    {
        $store = Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $query = Product::with(['categories', 'attributes', 'store', 'children'])
            ->where('store_id', $store->id)
            ->whereNull('parent_id'); // Only show main products, not sub-products

        // تطبيق فلتر الكلمة المفتاحية
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($catQuery) use ($keyword) {
                        $catQuery->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('attributes', function ($attrQuery) use ($keyword) {
                        $attrQuery->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        // تطبيق فلتر الفئة
        if ($categoryFilter) {
            $query->whereHas('categories', function ($q) use ($categoryFilter) {
                $q->where('categories.id', $categoryFilter);
            });
        }

        // تطبيق فلتر الحالة
        if ($statusFilter) {
            switch ($statusFilter) {
                case 'active':
                    $query->where('status', 'active');
                    break;
                case 'inactive':
                    $query->where('status', 'inactive');
                    break;
                case 'draft':
                    $query->where('status', 'draft');
                    break;
            }
        }

        return $query->paginate(20);
    }

    public function create(array $data)
    {
        try {
            // إنشاء المنتج
            // ملاحظة: ربط الفئات يتم في الـ Service وليس هنا
            $product = Product::create($data);

            return $product;
        } catch (\Exception $e) {
            Log::error('Product creation in repository failed: ' . $e->getMessage());
            Log::error('Data: ' . json_encode($data));
            throw $e;
        }
    }

    public function find(int $id)
    {
        return Product::with(['categories', 'attributes', 'children', 'parent'])->find($id);
    }

    public function update(int $id, array $data)
    {
        $product = Product::find($id);
        if (! $product) {
            abort(404);
        }

        $product->update($data);

        if (isset($data['category']) && $data['category']) {
            $product->categories()->sync([$data['category']]);
        } elseif (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function getTopOrderedProductsByStore($storeId, $limit = 10)
    {
        return Product::where('store_id', $storeId)
            ->where('status', true)
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get();
    }

    public function getTopViewedProductsByStore($storeId, $limit = 10)
    {
        return Product::where('store_id', $storeId)
            ->where('status', true)
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }
}
