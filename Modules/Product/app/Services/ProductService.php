<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Traits\TranslatableTrait;
use Modules\Product\Models\Product;
use Modules\Product\Repositories\ProductRepository;

class ProductService
{
    use \Modules\Core\Traits\ImageTrait, TranslatableTrait;

    public function __construct(
        protected ProductRepository $productRepository,
        protected \Modules\Attribute\Services\AttributeService $attributeService

    ) {}

    public function getAllProducts(?string $keyword = null, ?int $categoryFilter = null, ?string $statusFilter = null)
    {
        return $this->productRepository->index($keyword, $categoryFilter, $statusFilter);
    }

    /**
     * Translate given fields to all supported languages automatically.
     *
     * @param  array|null  $fields  Fields to translate, if null will translate all string fields
     */
    private function prepareData(array $data, ?array $fields = null): array
    {
        $locale = app()->getLocale();
        $fieldsToTranslate = $fields ?? array_keys($data);

        foreach ($fieldsToTranslate as $field) {
            if (isset($data[$field])) {

                // إذا كان array (multi-language) استخرج النسخة الحالية
                $original = is_array($data[$field]) ? ($data[$field][$locale] ?? reset($data[$field])) : $data[$field];

                // ضمان أن يكون string
                $original = $original ?? '';

                $translated = [$locale => $original];

                // استخدم النص الأصلي لجميع اللغات مؤقتاً لتجنب مشاكل الترجمة
                foreach ($this->otherLangs() as $lang) {
                    try {
                        // تجنب استخدام Google Translate مؤقتاً
                        $translated[$lang] = $original; // استخدام النص الأصلي
                        // $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: ".$e->getMessage());
                        $translated[$lang] = $original; // fallback
                    }
                }

                $data[$field] = $translated;
            }
        }

        return $data;
    }

    public function createProduct(array $data)
    {
        DB::beginTransaction();

        try {
            // الحصول على المتجر الحالي
            $store = \Modules\Store\Models\Store::currentFromUrl()->first();
            if (! $store) {
                $store = \Modules\Store\Models\Store::where('status', 'active')->first();
                if (! $store) {
                    throw new \Exception('No active store found');
                }
            }
            $data['store_id'] = $store->id;

            // إضافة القيم الافتراضية المطلوبة
            $data['status'] = $data['status'] ?? 'active';
            $data['is_active'] = $data['is_active'] ?? true;
            $data['is_featured'] = $data['is_featured'] ?? false;
            $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
            $data['min_quantity'] = $data['min_quantity'] ?? 1;
            $data['max_quantity'] = $data['max_quantity'] ?? 10;
            $data['product_type'] = $data['product_type'] ?? 'transfer';
            $data['notes'] = $data['notes'] ?? null;
            $data['views_count'] = 0;
            $data['orders_count'] = 0;

            // تنظيف البيانات - إزالة الحقول غير الضرورية والحقول غير الموجودة في قاعدة البيانات
            $data = array_filter($data, function($key) {
                $allowedFields = ['store_id', 'parent_id', 'name', 'description', 'price',
                                  'original_price', 'status', 'is_active', 'is_featured', 'sku',
                                  'stock_quantity', 'weight', 'dimensions', 'seo_title', 'seo_description',
                                  'min_quantity', 'max_quantity', 'product_type', 'notes',
                                  'views_count', 'orders_count', 'image', 'names', 'value', 'unit',
                                  'categories', 'category', 'category_id'];
                return in_array($key, $allowedFields);
            }, ARRAY_FILTER_USE_KEY);

            // إزالة الحقول غير الموجودة في قاعدة البيانات
            unset($data['linking_type']); // هذا الحقل غير موجود في قاعدة البيانات

            Log::info('Data after cleanup:', $data);

            // التعامل مع description
            if (!isset($data['description']) || $data['description'] === null) {
                if (isset($data['parent_id']) && $data['parent_id']) {
                    // منتج فرعي - جرب نسخ الوصف من الأب
                    $parentProduct = Product::find($data['parent_id']);
                    if ($parentProduct && $parentProduct->description) {
                        $data['description'] = $parentProduct->description;
                        Log::info('Copied description from parent product');
                    } else {
                        $data['description'] = ''; // empty string
                    }
                } else {
                    // منتج رئيسي بدون وصف
                    $data['description'] = '';
                }
            }

            // تحويل description و name إلى multilang
            $textFields = ['name', 'description'];
            $translated = $this->prepareData(array_intersect_key($data, array_flip($textFields)));

            $data = array_merge($data, $translated);

            // Log data for debugging
            Log::info('Product creation data: ' . json_encode($data));

            $product = $this->productRepository->create($data);
            Log::info('Product created with ID: ' . $product->id);

            // ربط الفئات حسب نوع المنتج
            $categoriesAssigned = false;

            if (isset($data['parent_id']) && $data['parent_id']) {
                // منتج فرعي: نسخ الفئات من المنتج الأب
                Log::info('This is a sub-product, parent_id: ' . $data['parent_id']);
                $parentProduct = Product::with('categories')->find($data['parent_id']);

                if ($parentProduct) {
                    Log::info('Parent product found: ' . $parentProduct->id);

                    if ($parentProduct->categories()->exists()) {
                        $parentCategories = $parentProduct->categories->pluck('id')->toArray();
                        Log::info('Parent product categories: ' . json_encode($parentCategories));

                        if (!empty($parentCategories)) {
                            $product->categories()->sync($parentCategories);
                            Log::info('Categories copied from parent to sub-product');
                            $categoriesAssigned = true;
                        }
                    } else {
                        Log::warning('Parent product has no categories! Sub-product will be created without categories.');
                        $categoriesAssigned = true; // لا نحاول ربط فئات أخرى
                    }
                } else {
                    Log::error('Parent product not found: ' . $data['parent_id']);
                }
            }

            // ربط الفئة للمنتجات الرئيسية (إذا لم يتم ربطها بعد)
            if (!$categoriesAssigned) {
                if (isset($data['category']) && $data['category']) {
                    $product->categories()->sync([$data['category']]);
                    Log::info('Assigned category to main product: ' . $data['category']);
                    $categoriesAssigned = true;
                } elseif (isset($data['categories']) && is_array($data['categories']) && !empty($data['categories'])) {
                    $product->categories()->sync($data['categories']);
                    Log::info('Assigned categories to main product: ' . json_encode($data['categories']));
                    $categoriesAssigned = true;
                }
            }

            if (!$categoriesAssigned) {
                Log::warning('No categories assigned to product: ' . $product->id);
            }

            if (isset($data['image'])) {
                Log::info('Uploading image for product: ' . $product->id);
                Log::info('Image file: ' . ($data['image'] ? $data['image']->getClientOriginalName() : 'null'));

                $this->uploadOrUpdateImageWithResize(
                    $product,
                    $data['image'],
                    'product_images',
                    'public',
                    false
                );

                // Check if image was uploaded successfully
                $media = $product->getFirstMedia('product_images');
                if ($media) {
                    Log::info('Image uploaded successfully: ' . $media->getUrl());
                } else {
                    Log::error('Failed to upload image for product: ' . $product->id);
                }
            }

            // Handle attributes if provided
            if (isset($data['names']) && ! empty($data['names']) && is_array($data['names'])) {
                foreach ($data['names'] as $k => $name) {
                    if (empty($name)) continue;

                    $valueData = ['value' => $data['value'][$k] ?? ''];
                    if (! empty($valueData)) {
                        $valueData = $this->prepareData($valueData);
                    }
                    // إنشاء أو إيجاد الخاصية
                    $attribute = $this->attributeService->create([
                        'name' => $name,
                        'unit' => $data['unit'][$k] ?? null,
                    ]);

                    // ربط الخاصية بالمنتج + القيمة
                    $product->attributes()->attach($attribute->id, [
                        'value' => ! empty($valueData['value']) ? json_encode($valueData['value']) : '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Product creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Data: ' . json_encode($data));
            throw $e;
        }
    }

    public function updateProduct(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->find($id);
            if (! $product) {
                abort(404, 'Product not found');
            }

            $textFields = ['name', 'description'];
            $translated = $this->prepareData(array_intersect_key($data, array_flip($textFields)));
            $data = array_merge($data, $translated);

            $this->productRepository->update($id, $data);

            // تحديث الفئات
            if (isset($data['category'])) {
                $product->categories()->sync([$data['category']]);
            }

            if (isset($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $product,
                    $data['image'],
                    'product_images',
                    'public',
                    true
                );
            }
            // تحديث الخصائص
            if (! empty($data['names']) && is_array($data['names'])) {

                $syncData = [];
                $locale = app()->getLocale();

                foreach ($data['names'] as $k => $name) {
                    if (! $name) {
                        continue;
                    }

                    $valueData = ['value' => $data['value'][$k] ?? ''];
                    $valueData = $this->prepareData($valueData);

                    $attribute = $this->attributeService->create([
                        'name' => $name,
                        'unit' => $data['unit'][$k] ?? null,
                    ]);

                    $syncData[$attribute->id] = [
                        'value' => ! empty($valueData['value']) ? json_encode($valueData['value']) : '',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                }

                // إعادة ربط الخصائص بالمنتج مع حذف القديمة غير الموجودة
                $product->attributes()->sync($syncData);
            }

            DB::commit();

            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProduct(Product $product)
    {
        DB::beginTransaction();

        try {

            $this->productRepository->delete($product);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
