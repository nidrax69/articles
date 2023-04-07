<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Register a new user
Route::post('/register', [AuthController::class, 'register']);

// Login a user
Route::post('/login', [AuthController::class, 'login']);

// Logout a user
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// GET all articles
Route::get('articles', [ArticleController::class, 'index'])->middleware('auth:sanctum');

// GET a single article
Route::get('articles/{article}', [ArticleController::class, 'show'])->middleware('auth:sanctum');

// POST a new article
Route::post('articles', [ArticleController::class, 'store'])->middleware('auth:sanctum');

// PUT/PATCH an existing article
Route::put('articles/{article}', [ArticleController::class, 'update'])->middleware('auth:sanctum');
Route::patch('articles/{article}', [ArticleController::class, 'update'])->middleware('auth:sanctum');

// DELETE an article
Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->middleware('auth:sanctum');
