<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Private routes
Route::middleware('jwt.verify')->group(function(){
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::get('user', [UserController::class, 'getAuthUser']);
    Route::post('logout', [UserController::class, 'logout']);
});

//Public routes
    // - Auth routes
Route::post('login', [UserController::class, 'authenticate']);
Route::post('register', [UserController::class, 'register']);
    // - Article routes
Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{article}', [ArticleController::class, 'show']);
Route::get('articles/search/{name}', [ArticleController::class, 'search']);

