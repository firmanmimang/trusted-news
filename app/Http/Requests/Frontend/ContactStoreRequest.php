<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
            'email' => ['required', 'max:256', 'string', 'email'],
            'nomor_telepon' => ['required', 'regex:/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/'],
            'pesan' => ['required', 'string', 'regex:/^[a-zA-Z0-9.,?\-!"\s\']+$/'],
        ];
    }
}
