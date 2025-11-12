<?php

namespace Modules\Product\Repositories;

use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;

class ProductModelRepository implements ProductRepository
{
    public function index(?string $keyword = null, ?int $categoryFilter = null, ?string $statusFilter = null, ?string $productTypeFilter = null, ?string $stockStatusFilter = null)
    {
        $store = Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $query = Product::with(['categories', 'attributes', 'store', 'children'])
            ->where('store_id', $store->id)
            ->whereNull('parent_id'); // Only show main products, not sub-products

        // تطبيق فلتر الكلمة المفتاحية - دعم البحث بحرف أو حرفين
        if ($keyword && strlen(trim($keyword)) >= 1) {
            $keyword = trim($keyword);
            $query->where(function ($q) use ($keyword) {
                // البحث في الحقول متعددة اللغات (JSON)
                $locale = app()->getLocale();
                $q->whereRaw("JSON_EXTRACT(name, '$.{$locale}') LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$keyword}%"])
                    // البحث في الوصف
                    ->orWhereRaw("JSON_EXTRACT(description, '$.{$locale}') LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("JSON_EXTRACT(description, '$.en') LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("JSON_EXTRACT(description, '$.ar') LIKE ?", ["%{$keyword}%"])
                    // البحث في الفئات
                    ->orWhereHas('categories', function ($catQuery) use ($keyword) {
                        $catQuery->where('name', 'like', "%{$keyword}%");
                    })
                    // البحث في الخصائص
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

        // تطبيق فلتر نوع المنتج
        if ($productTypeFilter) {
            $query->where('product_type', $productTypeFilter);
        }

        // تطبيق فلتر حالة المخزون
        if ($stockStatusFilter) {
            switch ($stockStatusFilter) {
                case 'in_stock':
                    // المنتجات المتوفرة (max_quantity > 0 أو null)
                    $query->where(function ($q) {
                        $q->whereNull('max_quantity')
                            ->orWhere('max_quantity', '>', 0);
                    });
                    break;
                case 'low_stock':
                    // المنتجات قليلة المخزون (max_quantity بين 1 و 10)
                    $query->whereBetween('max_quantity', [1, 10]);
                    break;
                case 'out_of_stock':
                    // المنتجات غير المتوفرة (max_quantity = 0 أو null مع min_quantity = 0)
                    $query->where(function ($q) {
                        $q->where('max_quantity', 0)
                            ->orWhere(function ($q2) {
                                $q2->whereNull('max_quantity')
                                    ->where(function ($q3) {
                                        $q3->where('min_quantity', 0)
                                            ->orWhereNull('min_quantity');
                                    });
                            });
                    });
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
            ->where('status', 'active')
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get();
    }

    public function getTopViewedProductsByStore($storeId, $limit = 10)
    {
        return Product::where('store_id', $storeId)
            ->where('status', 'active')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function getSubProducts($id, $storeId, ?string $query = null)
    {
        $product = Product::where('id', $id)
            ->where('store_id', $storeId)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return collect(); // مجموعة فارغة إذا المنتج غير موجود
        }

        $queryBuilder = $product->children()->orderBy('created_at', 'desc');

        if ($query) {
            $queryBuilder->where('name', 'like', "%{$query}%");
        }

        return $queryBuilder->get();
    }
}
