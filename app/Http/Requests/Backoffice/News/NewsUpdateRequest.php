<?php

namespace App\Http\Requests\Backoffice\News;

use App\Helpers\RegexHelper;
use Illuminate\Foundation\Http\FormRequest;

class NewsUpdateRequest extends FormRequest
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
            'title' => ['required', 'string' ,'max:256'],
            'image_description' => ['nullable', 'string', 'max:256'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'description' => ["required", 'string', 'not_regex:'.RegexHelper::RTE_REGEX.''],
            'image' => request()->image ? ['required', 'image', 'mimes:jpeg,jpg,png', 'max:1024'] : [],
            'category' => ['required', 'numeric', 'exists:categories,id'],
            'publish_status'=> 'nullable|in:true',
            'comment_status'=> 'nullable|in:true',
        ];
    }
}
