<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProhibitedToUpdateIfPublished;
use App\Rules\PublishedStatusWithPublishedAt;
use Illuminate\Support\Facades\Log;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $articleId = $this->route('article');

        return [
            'title' => [
                'sometimes',
                'string',
                'max:128',
                'required_without:status',
                new ProhibitedToUpdateIfPublished($articleId),
            ],
            'content' => [
                'sometimes',
                'string',
                'required_without:status',
                new ProhibitedToUpdateIfPublished($articleId)
            ],
            'status' => [
                'sometimes',
                'in:draft,published',
                'required_without_all:title,content,published_at'
            ],
            'published_at' => [
                'sometimes',
                'date_format:Y-m-d\TH:i:sO',
                'after_or_equal:now',
                'required_without:status',
                new ProhibitedToUpdateIfPublished($articleId),
                new PublishedStatusWithPublishedAt
            ],
        ];
    }


}
