<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Attribute;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::paginate(1);
        return response()->json([
            'data' => new ArticleCollection($articles),
            'links' => $articles->toArray()['links'],
            'meta' => [
                'total' => $articles->toArray()['total'],
                'count' => $articles->toArray()['per_page'],
                'current_page' => $articles->toArray()['current_page'],
                'total_pages' => $articles->toArray()['last_page'],
            ],
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
        $article = new Article($request->all());
        $article->user_id = auth()->user()->id;
        $article->save();

        return response()->json([
            'data' => [
                new ArticleResource($article)
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
                new ArticleResource($article),
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

    /**
     * Search for a USER.
     */

    public function searchArticleByUser($id)
    {
        return response()->json([
            'data' => new ArticleCollection(Article::where('user_id', $id)->get()),
            'status' => 'success',
            'code' => 200,
            'message' => 'Articles retrieved successfully.'
        ]);
    }
}
