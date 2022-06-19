<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers'

], function ($router) {
    Route::middleware(['cors'])->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/', 'AuthController@getAllItems');
    Route::get('/me', 'AuthController@me');
    Route::get('/addQuestion', 'AuthController@addQuestion');
    Route::get('/addOption', 'AuthController@addOption');
    Route::get('/addAnswer', 'AuthController@addAnswer');
    Route::post('/deleteQuestion/{request?}', 'AuthController@deleteQuestion');
    Route::get('/getAllFavorites', 'AuthController@getAllFavorites');
    Route::get('/search/{request?}', 'AuthController@search');
    Route::get('/addItem/{request?}', 'AuthController@addItem');
    Route::get('/addCategory/{request?}', 'AuthController@addCategory');
    });

});
// Route::post('/register', [UserController::class, 'signUp']);
// Route::post('/login', [UserController::class, 'logIn']);
