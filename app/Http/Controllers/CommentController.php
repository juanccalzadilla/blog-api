<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Mail\NewComment;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Article $article)
    {

        return response()->json(
            [
                'data' => new CommentCollection($article->comments),
                'status' => 'success',
                'code' => 200,
                'message' => 'Comments listed successfully.'
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Article $article)
    {

        $validate = Validator::make($request->all(), [
            'content' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(
                [
                    'data' => [],
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Validation error.',
                    'errors' => $validate->errors()
                ],
                400
            );
        }

        $comment = new Comment($request->all());
        $comment->user_id = auth()->user()->id;
        $article->comments()->save($comment);

        // Mail::to($article->user)->send(new NewComment($comment));
        // Mail::to($article->user)->later(now()->addMinutes(1), new NewComment($comment));
        Mail::to($article->user)->queue(new NewComment($comment));
        


        
        return response()->json(
            [
                'data' => CommentResource::make($comment),
                'status' => 'success',
                'code' => 201,
                'message' => 'Comment created successfully.'
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article, Comment $comment)
    {
        return response()->json(
            [
                'data' => CommentResource::make($article->comments()->where('id', $comment->id)->firstOrfail()),
                'status' => 'success',
                'code' => 200,
                'message' => 'Comment listed successfully.'
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article, Comment $comment)
    {
        $comment = $article->comments()->where('id', $comment->id)->firstOrfail();
        $comment->update($request->all());

        return response()->json(
            [
                'data' => CommentResource::make($comment),
                'status' => 'success',
                'code' => 200,
                'message' => 'Comment updated successfully.'
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article, Comment $comment)
    {
        $article->comments()->where('id', $comment->id)->firstOrfail()->delete();
        
        return response()->json(
            [
                'data' => [],
                'status' => 'success',
                'code' => 200,
                'message' => 'Comment deleted successfully.'
            ],
            200
        );
    }
}
