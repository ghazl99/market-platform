<?php

namespace Modules\User\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($store) {
                    $exists = \Modules\User\Models\User::where('email', $value)
                        ->whereHas('stores', fn ($q) => $q->where('store_id', $store->id))
                        ->where('id', '!=', Auth::user()->id) 
                        ->exists();

                    if ($exists) {
                        $fail('هذا البريد الإلكتروني مستخدم بالفعل في هذا المتجر.');
                    }
                },
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
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
