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
            abort(404, 'Store not found');
        }

        // Paginate the results (20 per page)
        return Category::where('store_id', $store->id)
            ->whereNull('parent_id')
            ->with('children')
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
            abort(404, 'Store not found');
        }

        return Category::with('children')
            ->whereNotNull('parent_id')
            ->where('store_id', $store->id)
            ->get();
    }

    public function getAllSubcategoriesById($id): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
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
            abort(404, 'Store not found');
        }
        // Create main category
        $category = Category::create([
            'name' => $data['name'],
            'store_id' => $store->id,

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
            abort(404, 'Store not found');
        }
        // Update main category
        $category->update([
            'name' => $data['name'],
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

        // استخدم distinct لحل مشكلة duplicate عند belongsToMany
        return $queryBuilder->distinct('products.id')->paginate(10)->appends(['query' => $query]);
    }
}
