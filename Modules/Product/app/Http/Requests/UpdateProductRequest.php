<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Product\Models\Product;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Check if this is a provider-only update
        // If only provider_id and/or provider_product_number are present and have values,
        // and other required fields are empty or not present, make validation flexible
        $hasProviderFields = ($this->filled('provider_id') || $this->filled('provider_product_number'));
        $hasName = $this->filled('name');
        $hasDescription = $this->filled('description');
        $hasPrice = $this->filled('price');
        $hasCategory = $this->filled('category');
        $hasStatus = $this->filled('status');
        
        $hasRequiredFields = $hasName || $hasDescription || $hasPrice || $hasCategory || $hasStatus;

        // If only provider fields are being updated, make other fields optional
        if ($hasProviderFields && !$hasRequiredFields) {
            return [
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'original_price' => 'nullable',
                'price' => 'nullable',
                'category' => 'nullable|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
                'status' => 'nullable|string|in:active,inactive,draft',
                'is_featured' => 'nullable|boolean',
                'min_quantity' => 'nullable|integer|min:0',
                'max_quantity' => 'nullable|integer|min:1|gte:min_quantity',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:500',
                'product_type' => 'nullable|string|in:transfer,code',
                'linking_type' => 'nullable|string|in:automatic,manual',
                'provider_id' => 'nullable|exists:providers,id',
                'provider_product_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'nullable|boolean',
            ];
        }

        // Normal validation rules for full product update
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'original_price' => 'nullable',
            'price' => 'required',
            'category' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'status' => 'required|string|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean',
            'min_quantity' => 'nullable|integer|min:0',
            'max_quantity' => 'nullable|integer|min:1|gte:min_quantity',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'product_type' => 'nullable|string|in:transfer,code',
            'linking_type' => 'nullable|string|in:automatic,manual',
            'provider_id' => 'nullable|exists:providers,id',
            'provider_product_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // الحصول على المتجر الحالي
            $store = \Modules\Store\Models\Store::currentFromUrl()->first();
            if (!$store) {
                return;
            }

            // الحصول على المنتج الحالي (من route parameter)
            $productId = $this->route('product');
            $product = $productId ? Product::find($productId) : null;

            // استخراج الاسم العربي للتحقق من التكرار
            $nameAr = null;
            if ($this->has('names') && is_array($this->input('names')) && isset($this->input('names')['ar'])) {
                $nameAr = trim($this->input('names')['ar']);
            } elseif ($this->has('name')) {
                $nameAr = trim($this->input('name'));
            }

            if ($nameAr) {
                // التحقق من وجود منتج آخر بنفس الاسم العربي في نفس المتجر
                $query = Product::where('store_id', $store->id)
                    ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.ar")) = ?', [$nameAr]);

                // استثناء المنتج الحالي من التحقق (عند التحديث)
                if ($product) {
                    $query->where('id', '!=', $product->id);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        'name',
                        __('A product with this name already exists in this store. Please choose a different name.')
                    );
                }
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
