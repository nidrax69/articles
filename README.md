# Zelty
Ce projet est une API Laravel pour gérer des articles.

## Dependencies
This project requires:

- PHP 8.2 or later
- Composer
- SQLite

## Installation

To install the project, follow these steps:

1. Clone the repository:
```sh
git clone https://github.com/nidrax69/zelty.git
```

2. Install the project:

```sh
cd zelty
#! Make sure to make the script executable 
chmod +x install.sh
./install.sh
```

This script launch :
- dependencies
- seeders
- tests
- migrations

The application will be available at ``http://localhost:8000``

3. Configure Postman to consume the API

- Import the file to Postman :  ``Articles.postman_collection.json``
- Import the environment file to postman : ``Zelty.postman_environment.json``

4. Configure environment 

Set the environment to Zelty and modify it : 
- name `Your name for registering`
- email `Your email for registering/login`
- password `Your password for registering/login`
- access_token `to set up automatically the access_token variable after registering/login`
- base_url `If you have a different url where the serve is available`

5. Consume it !

It's a standard RestFul API. 

## Routes

The following routes are available:
```php
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

// PUT an existing article
Route::put('articles/{article}', [ArticleController::class, 'update'])->middleware('auth:sanctum')->name('article');

// DELETE an article
Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->middleware('auth:sanctum');
```

> :warning: For updating the status of an article publish to draft , you need to only update the field status, or an error will be throw

6. For Launch Tests
```sh
php artisan test
```

