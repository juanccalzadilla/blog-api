<?php

use App\Models\Article;
use Database\Seeders\ArticlesTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->apiResource('articles', 'ArticleController')->except('index', 'show');

Route::get('/articles', function () {
    return response()->json([
        'success' => true,
        'data' => 
            Article::all()
    ]);
});

Route::get('/articles/{id}', function ($id) {
    return response()->json([
        'success' => true,
        'data' => 
            Article::find($id)
    ]);
});

Route::post('/articles', function (Request $request) {
    $article = Article::create([
        'title' => $request->title,
        'body' => $request->body
    ]);

    return $article;
});

Route::put('/articles/{id}', function (Request $request, $id) {
    $article = Article::findOrFail($id);
    return $article->update($request->all());
});

Route::delete('/articles/{id}', function ($id) {
    return Article::destroy($id);
});

Route::get('/articles/search/{title}', function ($title) {
    return Article::where('title', 'like', '%'.$title.'%')->get();
});