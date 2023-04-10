<?php

namespace App\Services;

use App\Exceptions\ArticleNotEditableException;
use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ArticleService
{
    public function getArticles(Request $request) : Paginator
    {
        $articles = Article::query();

        // Search by title and author
        if ($request->has('q')) {
            $search = $request->query('q');
            $articles->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                ->orWhereHas('author', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        // Sort by status (published or draft)
        if ($request->has('status')) {
            $status = $request->query('status');
            $articles->where('status', $status);
        }

        $articles = $articles->paginate($request->query('per_page', 10));
        return $articles;
    }

    public function getArticle(Article $article): Article
    {
        return $article;
    }

    public function createArticle(array $data) : Article
    {
        // Get the authenticated user
        $user = auth()->user();

        $article = new Article($data);
        $article->author()->associate($user);

        if ($article->status == 'published') {
            $article->published_at = now();
        }

        $article->save();

        return $article;
    }


    public function updateArticle(array $data, Article $article): Article
    {
        $article->fill($data);

        if ($article->status == 'published') {
            $article->published_at = now();
        }

        $article->save();

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
    }
}
