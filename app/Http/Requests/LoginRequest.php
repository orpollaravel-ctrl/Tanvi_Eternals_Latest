<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)->first();
                    if ($user && $user->active != 1) {
                        $fail('Your account is inactive. Please contact administrator.');
                    }
                },
            ],
            'password' => 'required'
        ];
    }
}
