<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BullionRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('bullion_rate') ?? $this->id;

        return [
            'serial_no' => ['required', 'string', 'max:255'],
            'rate_cut_on_off' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
