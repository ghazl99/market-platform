<?php

namespace Modules\Cart\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'player_id' => 'nullable|string|max:255',
            // 'delivery_email' => 'nullable|email|max:255',
            // 'activation_code' => 'nullable|string|max:255',
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
