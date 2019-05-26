<?php

use Illuminate\Http\Request;

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

Route::post('register','RegisterController@register');
//Comment route


//Feedback route
Route::middleware('auth:api')->group( function () {

	Route::resource('feedbacks', 'FeedBackController');

});

Route::middleware('auth:api')->get('feedbacks/public/place/{id}','FeedBackController@feedbackPublicById');
Route::middleware('auth:api')->get('feedbacks/private/place','FeedBackController@feedbackPrivateById');
// Route::get('hello','FeedBackController@index');
//Place route
Route::middleware('auth:api')->group( function () {

	Route::resource('places', 'PlaceController');

});
Route::middleware('auth:api')->get('get_current_user_info','UserController@getCurrentUserInfo');
//News route
Route::middleware('auth:api')->group( function () {

	Route::resource('news', 'NewsController');

});

Route::middleware('auth:api')->group( function () {

	Route::resource('comments', 'CommentController');

});
Route::middleware('auth:api')->get('comments/feedback/{id}','CommentController@index');

Route::middleware('auth:api')->get('news/place/{id}','NewsController@newsById');
// Route::middleware('auth:api')->post('addnews','NewsController@store');
// Route::resource('news','NewsController');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
