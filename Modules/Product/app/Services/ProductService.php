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

    public function getAllProducts(?string $keyword = null)
    {
        return $this->productRepository->index($keyword);
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

                foreach ($this->otherLangs() as $lang) {
                    try {
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
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
                abort(404, 'Store not found');
            }
            $data['store_id'] = $store->id;

            $textFields = ['name', 'description'];
            $translated = $this->prepareData(array_intersect_key($data, array_flip($textFields)));

            $data = array_merge($data, $translated);
            $product = $this->productRepository->create($data);
            if (isset($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $product,
                    $data['image'],
                    'product_images',
                    'private_media',
                    false
                );
            }
            if (! empty($data['names']) && is_array($data['names'])) {
                foreach ($data['names'] as $k => $name) {

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
            if (isset($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $product,
                    $data['image'],
                    'product_images',
                    'private_media',
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
