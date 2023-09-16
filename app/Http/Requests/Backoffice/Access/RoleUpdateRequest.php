<?php

namespace App\Http\Requests\Backoffice\Access;

use App\Helpers\GuardHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:256', 'regex:/^[a-zA-Z0-9.,?\-!"\s\']+$/'],
            'guard' => ['required', 'string', Rule::in(GuardHelper::guard())],
            'permission' => ['nullable', 'array', Rule::in(Permission::pluck('id'))],
        ];
    }
}
