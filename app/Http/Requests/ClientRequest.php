<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
        $clientId = $this->route('client'); 

        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'code')->ignore($clientId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'salesman_id' => 'nullable',
            'client_type' => 'required|in:Corporate,Job Work,B2B,SIS',
            'address_1' => 'nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'address_3' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'mobile_number' => 'nullable|regex:/^[6-9]\d{9}$/',
 
            'password' => $this->isMethod('post')
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
