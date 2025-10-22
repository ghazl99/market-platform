<?php

namespace Modules\Category\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Core\Traits\TranslatableTrait;

class CategoryService
{
    use \Modules\Core\Traits\ImageTrait, TranslatableTrait;

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Prepare data for multilingual fields (name, description)
     */
    private function prepareUserData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name', 'description'];

        // ترجمة الحقول الرئيسية
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $translated = [$locale => $data[$field]];

                // ترجمة إلى اللغة الأخرى المدعومة فقط
                $otherLang = $locale === 'ar' ? 'en' : 'ar';
                try {
                    $translated[$otherLang] = $this->autoGoogleTranslator($otherLang, $data[$field]);
                } catch (\Exception $e) {
                    Log::warning("Translation failed for [$field] to [$otherLang]: ".$e->getMessage());
                    // استخدام النص الأصلي كبديل
                    $translated[$otherLang] = $data[$field];
                } catch (\Throwable $e) {
                    Log::warning("Translation failed for [$field] to [$otherLang]: ".$e->getMessage());
                    // استخدام النص الأصلي كبديل
                    $translated[$otherLang] = $data[$field];
                }

                $data[$field] = $translated;
            }
        }

        // ترجمة الأصناف الفرعية إذا وجدت
        if (isset($data['subcategory_name']) && is_array($data['subcategory_name'])) {
            $translatedsubcategory_name = [];
            foreach ($data['subcategory_name'] as $subcategory) {
                $subTranslated = [$locale => $subcategory];

                // ترجمة إلى اللغة الأخرى المدعومة فقط
                $otherLang = $locale === 'ar' ? 'en' : 'ar';
                try {
                    $subTranslated[$otherLang] = $this->autoGoogleTranslator($otherLang, $subcategory);
                } catch (\Exception $e) {
                    Log::warning("Translation failed for [subcategory] to [$otherLang]: ".$e->getMessage());
                    // استخدام النص الأصلي كبديل
                    $subTranslated[$otherLang] = $subcategory;
                } catch (\Throwable $e) {
                    Log::warning("Translation failed for [subcategory] to [$otherLang]: ".$e->getMessage());
                    // استخدام النص الأصلي كبديل
                    $subTranslated[$otherLang] = $subcategory;
                }

                $translatedSubcategories[] = $subTranslated;
            }
            $data['subcategory_name'] = $translatedsubcategory_name;
        }

        return $data;
    }

    /** Get all parent categories with children */
    public function getAllcategories()
    {
        return $this->categoryRepository->index();
    }

    /** Get all subcategories */
    public function getAllSubcategories(): mixed
    {
        return $this->categoryRepository->getAllSubcategories();
    }

    /** Get all categories (main and subcategories) for product forms */
    public function getAllCategoriesForProducts(): mixed
    {
        return $this->categoryRepository->getAllCategoriesForProducts();
    }

    public function getAllSubcategoriesById($id): mixed
    {
        return $this->categoryRepository->getAllSubcategoriesById($id);
    }

    /** Get category by ID */
    public function getCategoryById($id): ?Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    /** Store new category with optional image */
    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            // إلغاء الترجمة التلقائية أثناء الإنشاء لتسريع الأداء
            // $data = $this->prepareUserData($data);

            // Ensure optional keys exist to avoid undefined index
            $data = array_merge([
                'parent_id' => $data['parent_id'] ?? null,
                'icon' => $data['icon'] ?? 'fas fa-tag',
                'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
                'sort_order' => $data['sort_order'] ?? 0,
                'seo_title' => $data['seo_title'] ?? null,
                'keywords' => $data['keywords'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
            ], $data);

            $category = $this->categoryRepository->store($data);

            // صورة الصنف الرئيسي
            if (! empty($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $category,
                    $data['image'],
                    'category_images',
                    'private_media',
                    false
                );
            }

            // الأصناف الفرعية
            if (! empty($data['subcategory_name'])) {
                foreach ($data['subcategory_name'] as $k => $name) {
                    if (! empty($name)) {
                        $subcategory = $this->categoryRepository->store([
                            'name' => $name,
                            'parent_id' => $category->id,
                            'store_id' => $data['store_id'] ?? null,
                        ]);

                        // صورة الصنف الفرعي
                        if (! empty($data['subcategory_image'][$k])) {
                            $this->uploadOrUpdateImageWithResize(
                                $subcategory,
                                $data['subcategory_image'][$k],
                                'subcategory_images',
                                'private_media',
                                false
                            );
                        }
                    }
                }
            }

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to store category: " . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /** Update existing category and optionally replace image */
    public function updateCategory(Category $category, array $data): bool
    {
        DB::beginTransaction();

        if (! $category) {
            return false;
        }
        $data = $this->prepareUserData($data);

        $this->categoryRepository->update($category, $data);

        if (isset($data['image'])) {
            $this->uploadOrUpdateImageWithResize(
                $category,
                $data['image'],
                'category_images',
                'private_media',
                true
            );
        }

        DB::commit();

        return true;
    }

    public function getProducts(Category $category, ?string $query = null)
    {
        return $this->categoryRepository->getProducts($category, $query);
    }

    /**
     * Delete a category and its associated data
     */
    public function deleteCategory(Category $category): bool
    {
        DB::beginTransaction();

        try {
            // Delete associated media
            $category->clearMediaCollection('category_images');

            // Delete subcategories first
            $category->children()->delete();

            // Delete the category
            $category->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to delete category: " . $e->getMessage(), [
                'category_id' => $category->id,
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
