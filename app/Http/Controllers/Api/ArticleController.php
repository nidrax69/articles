<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Renvoie tous les articles brouillon et publiés
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->articleService->getArticles();
        return response()->json(['data' => $articles]);
    }

    /**
     * Crée un nouvel article avec le statut brouillon ou publié
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->createArticle($request->validated());
        return response()->json(['data' => $article], 201);
    }

    /**
     * Renvoie l'article spécifié par son ID
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article = $this->articleService->getArticle($article);
        return response()->json(['data' => $article]);
    }

    /**
     * Met à jour un article qui est en brouillon
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article = $this->articleService->updateArticle($request->validated(), $article);
        return response()->json(['data' => $article]);
    }

    /**
     * Remove the specified Article.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $this->articleService->deleteArticle($article);
        return response()->json(['message' => 'Article deleted']);
    }
}
