<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\catagory;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\favorite;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'getAllItems', 'getItemsByCategory', 'getAllCategories', 'getItemById', 'search']]);
    }


    public function register()
    {

        $validator = validator()->make(request()->all(), [
            'username' => 'string|required',
            'email' => 'email|required',
            'password' => 'string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $user = User::create([
            'username' => request('username'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
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

    public function getAllItems()
    {
        $items = Item::with('category')->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ], 200);
    }
    //funtion to get all items by category
    public function getItemsByCategory(request $request)
    {
        $items = Item::with('category')->where('category_id', $request->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ], 200);
    }

    //function to get item by id
    public function getItemById(request $request)
    {
        $item = Item::where('id', $request->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $item
        ], 200);
    }

    //function to get all categories
    public function getAllCategories()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ], 200);
    }

    public function addToFavorite(request $request)
    {
        $user = Auth::user();
        $item = Item::find($request->id);
        $favorite = favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $favorite
        ], 200);
    }
    //function to get all favorites of user
    public function getAllFavorites()
    {
        $user = Auth::user();
        $favorites = favorite::where('user_id', $user->id)->get('item_id');
        $items = Item::whereIn('id', $favorites)->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ], 200);
    }

    public function search(request $request)
    {
        $items = Item::where('name', 'like', '%' . $request->search . '%')->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ], 200);
    }

    //fucntion let admin add new item
    public function addItem(request $request)
    {

        $path = $request->image->file('image')->store('public/images');
        $validatedData = $request->image->validate(['image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',]);
        $name = $request->image->file('image')->getClientOriginalName();
        $path = $request->image->file('image')->store('public/images');


        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $path,$name
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $path,$name
        ], 200);
    }
    //fucntion only admin add new category
    public function addCategory(request $request)
    {
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 200);
    }
}
