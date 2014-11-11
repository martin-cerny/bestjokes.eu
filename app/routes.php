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

Route::GET('/joke/{id}', 'JokeController@getJoke');
Route::GET('/addJoke', 'JokeController@addJoke');
Route::POST('/addJoke', 'JokeController@insertJoke');
Route::GET('/checkTitleDuplicity', 'JokeController@checkTitleDuplicity');
Route::GET('/checkTextDuplicity', 'JokeController@checkTextDuplicity');
Route::POST('/addVote', 'JokeController@addVote');
Route::GET('/{category}/{page?}',  array('as' => 'category', 'uses' => 'JokeController@getCategory'))->where('category', '[A-Z-a-z]+');
Route::GET('/{page?}', 'JokeController@getIndex');


