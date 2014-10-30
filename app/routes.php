<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::pattern('page', '[0-9]+');

Route::get('/joke/{id}', 'JokeController@getJoke');
Route::GET('/addJoke', 'JokeController@addJoke');
Route::POST('/addJoke', 'JokeController@insertJoke');
Route::post('/addVote', 'JokeController@addVote');
Route::get('/{category}/{page?}', 'JokeController@getCategory')->where('category', '[A-Z-a-z]+');
Route::get('/{page?}', 'JokeController@getIndex');


