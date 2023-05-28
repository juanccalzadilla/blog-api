<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    private static $messages =
    [
        'title.required' => 'El campo titulo es requerido.',
        'title.string' => 'El campo titulo debe ser un string.',
        'title.max' => 'El campo titulo debe tener maximo 255 caracteres.',
        'title.unique' => 'El campo titulo ya existe. Debe ser unico.',
        'body.required' => 'El campo body es requerido.',
        'body.string' => 'El campo body debe ser un string.',
        'category_id.required' => 'El campo category_id es requerido.',
        'category_id.integer' => 'El campo category_id debe ser un entero.',
        'category_id.exists' => 'El campo category_id debe existir en la tabla categories.',
        'image.required' => 'El campo image es requerido.',
        'image.image' => 'El campo image debe ser una imagen.',
        'image.mimes' => 'El campo image debe ser una imagen de tipo: jpeg, png, jpg, gif, svg.',
        'image.max' => 'El campo image debe tener maximo 2mb.',
        'image.dimensions' => 'El campo image debe tener minimo 100x100 pixeles.'

    ];

    private static $rules = [
        'title' => 'required|string|max:255|unique:articles',
        'body' => 'required|string',
        'category_id' => 'required|integer|exists:categories,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ArticleCollection(Article::paginate(2));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);
        $validate = Validator::make($request->all(), self::$rules, self::$messages);

        if ($validate->fails()) {
            return response()->json(['data' => [], 'status' => 'error', 'code' => 400, 'message' => 'Validation error.', 'errors' => $validate->errors()], 400);
        }

        $article = new Article($request->all());
        $path = $request->image->store('public/articles');
        $article->user_id = auth()->user()->id;
        // $path = $request->image->storeAs('public/articles', $request->user_id . '_' . $request->title . '.' . $request->image->extension());

        $article->image = $path;
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
        $this->authorize('update', $article);
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
        $this->authorize('delete', $article);
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
        return new ArticleCollection(Article::where('title', 'like', '%' . $name . '%')->get());
    }

    /**
     * Search for a USER.
     */

    public function searchArticleByUser($id)
    {
        return new ArticleCollection(Article::where('user_id', $id)->get());
    }

    /**
     * Get Article Image.
     */

    public function getImage(Article $article)
    {
        return response()->download(public_path(Storage::url($article->image)), $article->title);
    }
}
