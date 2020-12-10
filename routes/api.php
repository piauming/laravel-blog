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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', 'App\Http\Controllers\APIController@posts');
Route::get('/readposts', 'App\Http\Controllers\APIController@readposts');
Route::get('/users', 'App\Http\Controllers\APIController@users');
Route::get('/likes', 'App\Http\Controllers\APIController@likes');
Route::get('/followers', 'App\Http\Controllers\APIController@allfollowers');

Route::get('/followers/list/{userid}', 'App\Http\Controllers\APIController@followers');
Route::get('/followers/common/{userids}', 'App\Http\Controllers\APIController@common')->where('userids', '.*');
Route::get('/posts/unread/{userid}', 'App\Http\Controllers\APIController@unread');
Route::get('/posts/unread/{userid}/follower/{followerid}', 'App\Http\Controllers\APIController@unreadFollower');

