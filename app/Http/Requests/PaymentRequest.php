<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('payment') ?? $this->id;

        return [
           'serial_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payments', 'serial_no')->ignore($id),
            ], 
            'transaction_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payments', 'transaction_no')->ignore($id),
            ],
            'date' => ['required', 'date'],
            'client_code' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'transaction_no' => ['required','unique:payments,transaction_no'],
            'amount' => ['required', 'numeric', 'min:0'],
            'bank_cash' => ['required', 'string', 'max:255'],
        ];
    }
}
