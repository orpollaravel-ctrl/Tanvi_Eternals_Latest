<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BullionPurchaseRequest extends FormRequest
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
        $id = $this->route('bullion_purchase') ?? $this->id;

        return [
            'serial_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bullion_purchases', 'serial_no')->ignore($id),
            ],
            'transaction_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('bullion_purchases', 'transaction_no')->ignore($id),
            ],
            'transaction_date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'converted_weight' => ['required', 'numeric', 'min:0'],
            'purchase_rate' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
