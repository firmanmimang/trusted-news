<?php

namespace App\Http\Requests\Backoffice\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
        return [
            'new_password' => [
                'required',
                'confirmed',
                // Password::min(8)
                //     ->letters()
                //     ->mixedCase()
                //     ->numbers()
                //     ->symbols()
            ],
            'new_password_confirmation' => [
                'required'
            ],
        ];
    }
}
