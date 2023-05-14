<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => new ArticleCollection(Article::all()),
            'status' => 'success',
            'code' => 200,
            'message' => 'Articles retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
            'data' => [
                new ArticleResource(Article::create($request->all()))
            ],
            'status' => 'success',
            'code' => 201,
            'message' => 'Article created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return response()->json([
            'data' => [
                new ArticleResource($article)
            ],
            'status' => 'success',
            'code' => 200,
            'message' => 'Article retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $article = Article::find($article->id);
        $article->update($request->all());
        return response()->json(
            [
                'data' => [
                    new ArticleResource($article)
                ],
                'status' => 'success',
                'code' => 200,
                'message' => 'Article updated successfully.'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(
            [
                'data' => [],
                'status' => 'success',
                'code' => 204,
                'message' => 'Article deleted successfully.'
            ],
            204
        );
    }

    /**
     * Search for a name.
     */

    public function search($name)
    {
        return response()->json([
            'data' => new ArticleCollection(Article::where('title', 'like', '%' . $name . '%')->get()),
            'status' => 'success',
            'code' => 200,
            'message' => 'Articles retrieved successfully.'
        ]);
    }
}
