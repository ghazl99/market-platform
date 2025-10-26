<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // التحقق من نوع المنتج (فرعي أو رئيسي)
        $isSubProduct = $this->has('parent_id') && $this->parent_id;

        $rules = [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:products,id',
            'original_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'names' => 'nullable|array',
            'names.*' => 'string|max:255',
            'value' => 'nullable|array',
            'value.*' => 'nullable|string|max:255',
            'unit' => 'nullable|array',
            'unit.*' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'min_quantity' => 'nullable|integer|min:0',
            'max_quantity' => 'nullable|integer|min:1',
            'status' => 'nullable|string|in:active,inactive,draft',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|string|max:100',
            'dimensions' => 'nullable|string|max:100',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'product_type' => 'nullable|string|in:transfer,code',
            'linking_type' => 'nullable|string|in:automatic,manual',
            'notes' => 'nullable|string|max:1000',
        ];

        // قواعد مخصصة للمنتجات الفرعية والرئيسية
        if ($isSubProduct) {
            // المنتج الفرعي: الفئة والوصف اختياريين (سيتم نسخهما من الأب)
            $rules['category'] = 'nullable';
            $rules['description'] = 'nullable|string';
        } else {
            // المنتج الرئيسي: الفئة والوصف مطلوبان
            $rules['category'] = 'required|exists:categories,id';
            $rules['description'] = 'required|string';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The product name is required'),
            'description.required' => __('The product description is required'),
            'price.required' => __('The product price is required'),
            'stock_quantity.required' => __('The available quantity is required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
