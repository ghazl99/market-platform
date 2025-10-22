<?php

namespace Modules\Store\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $timezones = implode(',', array_slice(timezone_identifiers_list(), 0, 400));

        return [
            'name' => ['required', 'string', 'max:255','unique:stores,name'],

            'domain' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/', // حروف لاتينية فقط وأرقام وشرطة
                'min:3',
                'max:50',
                'unique:stores,domain',
            ],

            'description' => ['nullable', 'string'],

            'theme' => ['required', 'string', 'in:default,modern,classic'],

            'type' => ['required', 'string', 'in:traditional,digital,educational'],

            'user_id' => ['required', 'exists:users,id'],

            // ميم تايبز بشكل آمن لجميع الإصدارات
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:4096'],

            // نتحقق فقط من أنه timezone صالح من القائمة المسموحة
            'timezone' => ['required', 'string', "in:$timezones"],
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
