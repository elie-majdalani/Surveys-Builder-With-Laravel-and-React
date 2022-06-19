<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\catagory;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Question;
use App\Models\Option;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Middleware\Cors;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'getAllItems', 'addOption', 'addAnswer', 'deleteQuestion']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl')
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    //function get all questions with their options
    public function getAllItems()
    {
        $questions = Question::with('Option')->get();
        return response()->json([
            'status' => 'success',
            'data' => $questions
        ], 200);
    }
    //function add question to database
    public function addQuestion()
    {
        $validator = validator()->make(request()->all(), [
            'question' => 'string|required',
            'type' => 'string|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $question = Question::create([
            'question' => request('question'),
            'type' => request('type'),
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $question
        ], 200);
    }
    //function add option to database
    public function addOption()
    {
        $validator = validator()->make(request()->all(), [
            'question_id' => 'integer|required',
            'option' => 'string|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $option = Option::create([
            'question_id' => request('question_id'),
            'option' => request('option'),
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $option
        ], 200);
    }
    //function add answer to database
    public function addAnswer()
    {
        $validator = validator()->make(request()->all(), [
            'question_id' => 'integer|required',
            'answer' => 'string|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $option = Answer::create([
            'question_id' => request('question_id'),
            'answer' => request('answer'),
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $option
        ], 200);
    }
    //function delete question from database
    public function deleteQuestion(Request $request)
    {
        $question = Question::find($request->id);
        $question->delete();
        return response()->json([
            'status' => 'success',
            'data' => $question
        ], 200);
    }
}
