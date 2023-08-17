<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterStoreRequest extends FormRequest
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
            'nama' => ['required', 'max:256', 'regex:/^[a-zA-Z0-9.,?\-!"\s\']+$/'],
            'email' => [
                'required',
                'email',
                'max:256',
                Rule::unique('users', 'email')->where(function ($query) {
                    $query->whereNotNull('password');
                }),
            ],
            'password' => [
                'required',
                'confirmed',
                'max:256',
                // Password::min(8)
                //     ->letters()
                //     ->mixedCase()
                //     ->numbers()
                //     ->symbols()
            ],
            'password_confirmation' => [
                'required',
                'max:256',
            ],
        ];
    }
}
