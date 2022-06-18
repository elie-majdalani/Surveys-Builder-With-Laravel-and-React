<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('/getItemsByCategory/{request?}', 'AuthController@getItemsByCategory');
    Route::get('/getAllCategories', 'AuthController@getAllCategories');
    Route::get('/getItemById/{request?}', 'AuthController@getItemById');
    Route::post('/addToFavorite/{request?}', 'AuthController@addToFavorite');
    Route::get('/getAllFavorites', 'AuthController@getAllFavorites');
    Route::get('/search/{request?}', 'AuthController@search');
    Route::get('/addItem/{request?}', 'AuthController@addItem');
    Route::get('/addCategory/{request?}', 'AuthController@addCategory');
    });

});