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
                foreach ($this->otherLangs() as $lang) {
                    try {
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $data[$field]);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: " . $e->getMessage());
                        $translated[$lang] = $data[$field]; // fallback
                    }
                }
                $data[$field] = $translated;
            }
        }

        // ترجمة الأصناف الفرعية إذا وجدت
        if (isset($data['subcategory_name']) && is_array($data['subcategory_name'])) {
            $translatedsubcategory_name = [];
            foreach ($data['subcategory_name'] as $subcategory) {
                $subTranslated = [$locale => $subcategory];
                foreach ($this->otherLangs() as $lang) {
                    try {
                        $subTranslated[$lang] = $this->autoGoogleTranslator($lang, $subcategory);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [subcategory] to [$lang]: " . $e->getMessage());
                        $subTranslated[$lang] = $subcategory;
                    }
                }
                $translatedsubcategory_name[] = $subTranslated;
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

    public function getAllSubcategoriesById($id): mixed
    {
        return $this->categoryRepository->getAllSubcategoriesById($id);
    }

    /** Store new category with optional image */
    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $data = $this->prepareUserData($data);

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
}
