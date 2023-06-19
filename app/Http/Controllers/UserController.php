<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                    'code' => 400
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not create token',
                'code' => 500
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'code' => 200
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'editorial' => 'required|string',
            'short_bio' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'code' => 400
            ], 400);
        }

        $writer = Writer::create([
            'editorial' => $request->editorial,
            'short_bio' => $request->short_bio,
        ]);

        $writer->user()->create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $token = JWTAuth::fromUser($writer->user);

        return response()->json(
            [
                'status' => 'success',
                'data' => [
                    'user' => UserResource::make($writer->user),
                    'token' => $token
                ],
                'code' => 201
            ],
            201
        );
    }

    public function getAuthUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['token_absent'], 401);
        }
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
            'code' => 200
        ], 200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully',
                'code' => 200
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, the user cannot be logged out',
                'code' => 500
            ], 500);
        }
    }


    public function articles(){
        $articles = auth()->user()->articles;
        return response()->json([
            'status' => 'success',
            'data' => $articles,
            'code' => 200
        ], 200);
    }
}
