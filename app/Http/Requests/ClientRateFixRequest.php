<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientRateFixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('client_rate_fix') ?? $this->id; // route parameter (for update)

        return [
            'serial_no' => ['required', 'string', 'max:255'],
            'transaction_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('client_rate_fixs', 'transaction_no')->ignore($id),
            ],
            'transaction_date' => ['required', 'date'],
            'client_code' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'sales_person' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric'],
            'amount' => ['required', 'numeric', 'min:0'],
            'average' => ['nullable', 'numeric', 'min:0'],
            'profit_loss' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
