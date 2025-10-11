<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

interface CategoryRepository
{
    /**
     * Get all parent categories with their children.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index();

    /**
     * Get all subcategories (categories that have a parent).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSubcategories(): mixed;

    /**
     * Get all categories (main and subcategories) for product forms.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategoriesForProducts(): mixed;

    public function getAllSubcategoriesById($id): mixed;

    /**
     * Store a new category with optional subcategories.
     *
     * @return Category
     */
    public function store(array $data);

    /**
     * Find a category by its ID.
     *
     * @return Category|null
     */
    public function find(int $id): mixed;

    /**
     * Get category by ID with store validation.
     *
     * @return Category|null
     */
    public function getCategoryById($id): ?Category;

    /**
     * Update a category and replace its subcategories.
     *
     * @return Category|false
     */
    public function update(Category $category, array $data): mixed;

    public function getProducts(Category $category, ?string $query = null);
}
