<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Article;

class ProhibitedToUpdateIfPublished implements Rule
{
    protected Article $article;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($this->article->status !== 'published');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Cannot modify article if it is in a published status. Modify the status of the article first !';
    }
}
