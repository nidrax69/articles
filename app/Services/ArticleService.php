<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function getArticles()
    {
        return Article::all();
    }

    public function getArticle(Article $article)
    {
        return $article;
    }

    public function createArticle(array $data)
    {
        // Get the authenticated user
        $user = auth()->user();

        $article = new Article($data);

        if ($article->status == 'published') {
            $article->publication_date = now();
        }

        $article->save();

        return $article;
    }

    public function updateArticle(array $data, Article $article)
    {
        if ($article->status !== 'draft') {
            throw new \Exception('Cannot update a published or deleted article');
        }

        $article->fill($data);

        if ($article->status == 'published') {
            $article->publication_date = now();
        } else {
            $article->publication_date = null;
        }

        $article->save();

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
    }
}
