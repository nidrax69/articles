<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
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
    public function index(Request $request)
    {
        $articles = $this->articleService->getArticles($request);
        return new ArticleCollection($articles);
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
        return new ArticleResource($article, 201);
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
        return new ArticleResource($article, 200);
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
        return new ArticleResource($article, 200);
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
        return response()->noContent();
    }
}
