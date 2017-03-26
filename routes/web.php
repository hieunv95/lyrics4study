<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/hello', function () {
    return view('hello');
});

Auth::routes();

Route::get('home', 'Web\HomeController@index');
Route::get('/', 'Web\HomeController@index');
Route::group(['prefix' => 'lyrics'], function () {
    Route::get('search', 'Web\HomeController@search');
    Route::get('autocomplete', 'Web\HomeController@autocomplete');
    Route::get('show/{artist}/{title}/{id}', ['as' => 'show', 'uses' => 'Web\LyricController@show']);
    Route::get('play/{artist}/{title}/{id}/{level}', 'Web\LyricController@play');
    Route::post('check', 'Web\LyricController@check');
});
Route::group(['prefix' => 'history', 'middleware' => 'auth'], function () {
    Route::get('/', 'Web\UserController@history');
    Route::get('/detail/{lyric_id}', 'Web\UserController@detailHistory');
});
Route::get('/test', function () {
	return view('test');
});

Route::get('social/redirect/{provider}', 'Auth\SocialAuthController@redirect');
Route::get('social/handle/{provider}', 'Auth\SocialAuthController@handle');

