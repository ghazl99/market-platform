<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

class CategoryModelRepository implements CategoryRepository
{
    /**
     * Get all parent categories along with their children.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        // Paginate the results (20 per page)
        return Category::where('store_id', $store->id)
            ->whereNull('parent_id')
            ->with('children')
            ->with('media')
            ->get();
    }

    /**
     * Get all subcategories (categories that have a parent).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSubcategories(): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        return Category::with('children')
            ->whereNotNull('parent_id')
            ->where('store_id', $store->id)
            ->get();
    }

    /**
     * Get all categories (main and subcategories) for product forms.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategoriesForProducts(): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        return Category::with('children')
            ->where('store_id', $store->id)
            ->orderBy('parent_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getAllSubcategoriesById($id): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        return Category::with('children')
            ->where('store_id', $store->id)
            ->findOrFail($id);
    }

    /**
     * Store a new category with optional subcategories.
     *
     * @return Category
     */
    public function store(array $data)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        // Create main or sub category
        $category = Category::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'store_id' => $store->id,
            'parent_id' => $data['parent_id'] ?? null,
            'icon' => $data['icon'] ?? (!empty($data['parent_id']) ? 'fas fa-tag' : 'fas fa-tag'),
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
            'seo_title' => $data['seo_title'] ?? null,
            'keywords' => $data['keywords'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ]);

        // Create subcategories if provided
        if (! empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (! empty($subcategoryName)) {
                    Category::create([
                        'name' => $subcategoryName,
                        'parent_id' => $category->id, // Link child to parent
                        'store_id' => $store->id,

                    ]);
                }
            }
        }

        return $category;
    }

    /**
     * Find a category by ID.
     *
     * @return Category|null
     */
    public function find(int $id): mixed
    {
        return Category::find($id);
    }

    /**
     * Get category by ID with store validation.
     *
     * @return Category|null
     */
    public function getCategoryById($id): ?Category
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (!$store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (!$store) {
                abort(404, 'No active store found');
            }
        }

        return Category::where('store_id', $store->id)->find($id);
    }

    /**
     * Update a category and replace its subcategories.
     *
     * @return Category|false
     */
    public function update(Category $category, array $data): mixed
    {
        if (! $category) {
            return false;
        }
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        if (! $store) {
            // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::where('status', 'active')->first();
            if (! $store) {
                abort(404, 'No active store found');
            }
        }

        // Update main category
        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'icon' => $data['icon'] ?? 'fas fa-tag',
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
            'seo_title' => $data['seo_title'] ?? null,
            'keywords' => $data['keywords'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'store_id' => $store->id,
        ]);

        // Remove old subcategories
        $category->children()->delete();

        // Create new subcategories if provided
        if (! empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (! empty($subcategoryName)) {
                    $category->children()->create([
                        'name' => $subcategoryName,
                        'parent_id' => $category->id, // Link child to parent
                        'store_id' => $store->id,
                    ]);
                }
            }
        }

        return $category;
    }

    public function getProducts(Category $category, ?string $query = null)
    {
        $queryBuilder = $category->products()->orderBy('created_at', 'desc');

        if ($query) {
            $queryBuilder->where('name', 'like', "%{$query}%");
        }

        // إلغاء الباجينيشن واستخدام get()
        return $queryBuilder->distinct('products.id')->get();
    }
}
