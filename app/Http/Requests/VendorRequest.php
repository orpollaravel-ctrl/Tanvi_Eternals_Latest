<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'gst_number' => ['required', 'string', 'max:50'],
            'pan_number' => ['required', 'string', 'max:50'],
            'adhard_number' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'ifsc_code' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'party_name' => ['nullable', 'string', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'salesman' => ['nullable', 'string', 'max:255'],
        ];
    }
}
