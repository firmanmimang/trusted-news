<?php

namespace App\Http\Requests\Frontend;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'body' => ['required', 'string']
        ];
    }

    public function author(): User
    {
        return $this->user();
    }

    public function news(): News
    {
        return $this->news;
    }

    public function body(): string
    {
        return $this->get('body');
    }
}
