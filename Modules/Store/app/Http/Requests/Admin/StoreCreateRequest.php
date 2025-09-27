<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:stores,domain',
            'description' => 'nullable|string',
            'theme' => 'required|string',
            'status' => 'required|string|in:active,pending,inactive',
            'type' => 'required|string|in:traditional,digital',
            'user_id' => 'required|exists:users,id',
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'banner' => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'timezone' => 'required|string|in:'.implode(',', timezone_identifiers_list()),
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
