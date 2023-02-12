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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/',  'IndexController');

Route::post('/login', 'AuthController');
Route::group(['namespace' => 'Admin\Category', 'prefix' => 'admin'], function () {
    Route::get('/categories', 'IndexController');
    Route::post('/categories/create', 'StoreController');
    Route::patch('/categories/{category}', 'UpdateController');
    Route::delete('/categories/{category}', 'DeleteController');
});

Route::group(['namespace' => 'Admin\Tag', 'prefix' => 'admin'], function () {
    Route::get('/tags', 'IndexController');
    Route::post('/tags/create', 'StoreController');
    Route::patch('/tags/{tag}', 'UpdateController');
    Route::delete('/tags/{tag}', 'DeleteController');
});

Route::group(['namespace' => 'Admin\User', 'prefix' => 'admin'], function () {
    Route::get('/users', 'IndexController');
    Route::post('/users/create', 'StoreController');
    Route::patch('/users/{user}', 'UpdateController');
    Route::delete('/users/{user}', 'DeleteController');
});

Route::group(['namespace' => 'Admin\Post', 'prefix' => 'admin'], function () {
    Route::get('/posts', 'IndexController');
    Route::get('/post/{id}', 'ShowController');
    Route::post('/post/create', 'StoreController');
    Route::patch('/post/{id}', 'UpdateController');
    Route::delete('/post/{id}', 'DeleteController');
});

Route::group(['namespace' => 'Admin\Comment', 'prefix' => 'admin'], function () {
    // Route::get('/posts', 'IndexController');
    // Route::get('/post/{id}', 'ShowController');
    Route::post('/comment/create', 'StoreController');
    // Route::patch('/post/{id}', 'UpdateController');
    // Route::delete('/post/{id}', 'DeleteController');
});




// Route::get('/admin/posts', 'App\Http\Controllers\Admin\Post\IndexController');
