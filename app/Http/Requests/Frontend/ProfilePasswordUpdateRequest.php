<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilePasswordUpdateRequest extends FormRequest
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
            'password_sekarang' => auth()->user()->password ? [
                'required',
                'current_password'
                // function ($attribute, $value, $fail) {
                //     if (!Hash::check($value, auth()->user()->password)) {
                //         $fail(trans('auth.password'));
                //     }
                // },
            ] : '',
            'password_baru' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    // ->symbols()
                    ,
                'confirmed',
            ],
            'password_baru_confirmation' => [
                'required'
            ],
        ];
    }
}
