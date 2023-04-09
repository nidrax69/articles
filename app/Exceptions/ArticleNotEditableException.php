<?php

namespace App\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ArticleNotEditableException extends Exception
{
    protected $message = 'This article cannot be edited because it is not in draft status.';

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response($this->message, 422);
    }
}
