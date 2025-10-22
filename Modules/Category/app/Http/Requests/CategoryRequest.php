<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'icon' => 'required|string',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'seo_title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:500',
            'seo_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'subcategories' => 'nullable|array',
            'subcategories.*' => 'nullable|string|max:255',
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
