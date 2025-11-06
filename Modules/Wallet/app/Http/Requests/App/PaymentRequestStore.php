<?php

namespace Modules\Wallet\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string',
            'wallet_id' => 'required|exists:wallets,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'receipt_image' => 'required|image|mimes:jpg,jpeg,png,pdf',
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
