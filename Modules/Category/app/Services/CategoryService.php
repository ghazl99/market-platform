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
     * Translate given fields to all supported languages automatically.
     * Prepares data for multilingual fields (name, description, etc.)
     *
     * @param  array  $data  Data array containing fields to translate
     * @param  array|null  $fields  Fields to translate, if null will translate all string fields
     * @return array Data with translated fields
     */
    private function prepareUserData(array $data, ?array $fields = null): array
    {
        $locale = app()->getLocale();

        // إذا لم يتم تحديد الحقول، استخدم الحقول الافتراضية
        if ($fields === null) {
            $fields = ['name', 'description']; // الحقول الافتراضية للترجمة
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                // إذا كان الحقل array بالفعل (multi-language)، استخرج النسخة الحالية
                if (is_array($data[$field])) {
                    $original = $data[$field][$locale] ?? reset($data[$field]);
                } else {
                    $original = $data[$field];
                }

                // ضمان أن يكون string
                if (!is_string($original)) {
                    $original = (string)($original ?? '');
                }

                // إذا كان النص فارغاً، تخطاه
                if (empty(trim($original))) {
                    continue;
                }

                // حفظ النص الأصلي باللغة الحالية
                $translated = [$locale => $original];

                // ترجمة إلى جميع اللغات الأخرى
                foreach ($this->otherLangs() as $lang) {
                    try {
                        // استخدام Google Translate للترجمة التلقائية
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: " . $e->getMessage());
                        // في حالة فشل الترجمة، استخدم النص الأصلي كبديل
                        $translated[$lang] = $original;
                    }
                }

                // استبدال الحقل بنسخة متعددة اللغات
                $data[$field] = $translated;
            }
        }

        // ترجمة الأصناف الفرعية إذا وجدت
        if (isset($data['subcategory_name']) && is_array($data['subcategory_name'])) {
            $translatedSubcategories = [];
            foreach ($data['subcategory_name'] as $subcategory) {
                if (empty($subcategory)) {
                    continue;
                }

                // إذا كان الحقل array بالفعل (multi-language)، استخرج النسخة الحالية
                if (is_array($subcategory)) {
                    $original = $subcategory[$locale] ?? reset($subcategory);
                } else {
                    $original = $subcategory;
                }

                // ضمان أن يكون string
                if (!is_string($original)) {
                    $original = (string)($original ?? '');
                }

                // إذا كان النص فارغاً، تخطاه
                if (empty(trim($original))) {
                    continue;
                }

                $subTranslated = [$locale => $original];

                // ترجمة إلى جميع اللغات الأخرى
                foreach ($this->otherLangs() as $lang) {
                    try {
                        $subTranslated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [subcategory] to [$lang]: " . $e->getMessage());
                        $subTranslated[$lang] = $original;
                    }
                }

                $translatedSubcategories[] = $subTranslated;
            }
            $data['subcategory_name'] = $translatedSubcategories;
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
            // تحويل name و description إلى multilang
            $textFields = ['name', 'description'];
            // إعداد البيانات للترجمة - تمرير البيانات الكاملة لمعالجة subcategory_name أيضًا
            $dataForTranslation = array_intersect_key($data, array_flip($textFields));
            if (isset($data['subcategory_name'])) {
                $dataForTranslation['subcategory_name'] = $data['subcategory_name'];
            }
            $translated = $this->prepareUserData($dataForTranslation);

            $data = array_merge($data, $translated);

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
                    'public',
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
                                'public',
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
                'public',
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
