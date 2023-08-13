<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuestStoreRequest extends FormRequest
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
            'umur' => ['required', 'min:0', 'numeric'],
            'email' => ['required', 'max:256', 'string', 'email'],
            'nomor_telepon' => ['required', 'regex:/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/'],
            'pesan_dan_saran' => ['nullable'],
            'kelamin' => ['required', Rule::in(['male', 'female'])],
            'rating' => ['required', Rule::in([1, 2, 3, 4, 5])],
        ];
    }
}
