<?php

namespace App\Services;

use App\Exceptions\ArticleNotEditableException;
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
        $article->user()->associate($user);

        if ($article->status == 'published') {
            $article->publication_date = now();
        }

        $article->save();

        return $article;
    }

    public function updateArticleStatus(array $_article, Article $article)
    {
        $article->status = $_article["status"];
        $article->save();

        return $article;
    }

    public function updateArticle(array $data, Article $article)
    {
        if ($article->status !== 'draft') {
            throw new ArticleNotEditableException();
        }

        $article->fill($data);

        if ($article->status == 'published') {
            $article->publish_date = now();
        } else {
            $article->publish_date = null;
        }

        $article->save();

        return $article;
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
    }
}
