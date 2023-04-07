<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Article::class) || false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            'title' => 'required|string|max:128',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'publication_date' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:today',
        ];
    }
}
