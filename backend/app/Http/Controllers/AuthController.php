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
        $this->middleware('auth:api', ['except' => ['login', 'addQuestion', 'getAllItems', 'addOption', 'addAnswer', 'deleteQuestion', 'search']]);
    }

    public function login()
    {
        $data = request()->getContent();
        $data = json_decode($data, true);


        if (!$token = JWTAuth::attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'status' => 'success',
            'data' => $token
        ], 200);
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

        //$data = $request->all();

        $data = request()->getContent();
        $data = json_decode($data, true);
        $rules = [
            'question_id' => 'integer|required',
            'options' => 'array|required',
        ];
        $validator = validator()->make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        echo ($data['options']);
        if ($data['options']) {
            foreach ($data['options'] as $option) {
                Option::create([
                    'question_id' => $data['question_id'],
                    'option' => $option
                ]);
            }
        }


        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    //function add answer to database
    public function addAnswer()
    {
        $data = request()->getContent();
        $data = json_decode($data, true);
        $data = $data['userAnswers'];
        $counter = -1;
        foreach ($data as $answer) {
            $counter = $counter + 1;
            $key = array_keys($data)[$counter];
            if (gettype($answer) == "array") {
                foreach ($answer as $ans) {
                    Answer::create([
                        'question_id' => $key,
                        'answer' => $ans
                    ]);
                }
            } else {
                Answer::create([
                    'question_id' => $key,
                    'answer' => $answer
                ]);
            }
        }
        return response()->json([
            'status' => 'success'
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
