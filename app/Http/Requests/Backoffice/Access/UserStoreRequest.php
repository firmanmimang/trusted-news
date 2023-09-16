<?php

namespace App\Http\Requests\Backoffice\Access;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'avatar' => request()->avatar ? ['required', 'image', 'mimes:png', 'max:1024'] : '',
            'name' => ['required', 'string', 'max:256', 'regex:/^[a-zA-Z0-9.,?\-!"\s\']+$/'],
            'username' => ['required', 'string', 'unique:users,username', 'max:256'],
            'email' => ['required', 'email', 'unique:users,email', 'max:256'],
            'password' => [
                'required',
                'confirmed',
                'max:256',
            ],
            'password_confirmation' => [
                'required',
                'max:256',
            ],
            'role' => [
                'required',
                'string',
                'exists:roles,id'
            ],
        ];
    }
}
