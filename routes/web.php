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
    Route::group(['middleware' => 'auth'], function() {
        Route::get('create', 'Web\LyricController@create');
        Route::post('save', 'Web\LyricController@save');
    });
});
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/history', 'Web\UserController@history');
    Route::get('/detailHistory/{lyric_id}', 'Web\UserController@detailHistory');
    Route::get('showlyrics/{user_id}', 'Web\UserController@showLyrics');
    Route::post('saveScore', 'Web\UserController@saveScore');
});

Route::get('social/redirect/{provider}', 'Auth\SocialAuthController@redirect');
Route::get('social/handle/{provider}', 'Auth\SocialAuthController@handle');

