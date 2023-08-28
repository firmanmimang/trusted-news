<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'photo_profile' => request()->photo_profile ? ['required', 'image', 'mimes:jpeg,jpg,png', 'max:1024'] : '',
            'nama' => ['required', 'max:256', 'regex:/^[a-zA-Z0-9.,?\-!"\s\']+$/'],
            'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'max:256'],
        ];
    }
}
