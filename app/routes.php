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
Route::pattern('id', '[0-9]+');


Route::get('/create', function()
{
    $user = new User;
    $user->email = 'khushbu.k017@gmail.com';
    $user->username = 'indo';
    $user->password = Hash::make('girl07');
    $user->save();
});

Route::GET('/addJoke', array('as' => 'addJoke', 'before' => 'auth.basic', 'uses' => 'JokeController@addJoke'));
Route::POST('/addJoke', array('as' => 'addJoke', 'before' => 'auth.basic', 'uses' => 'JokeController@insertJoke'));
Route::GET('/editJoke/{id}', array('as' => 'editJoke', 'before' => 'auth.basic', 'uses' => 'JokeController@editJoke'));
Route::POST('/deleteJoke/{id}', array('as' => 'deleteJoke', 'before' => 'auth.basic', 'uses' => 'JokeController@deleteJoke'));
Route::POST('/updateJoke/{id}', array('as' => 'updateJoke', 'before' => 'auth.basic', 'uses' => 'JokeController@updateJoke'));
Route::GET('/admin', array('before' => 'auth.basic', 'uses' => 'AdminController@login'));

Route::GET('/joke/{id}', array('as' => 'joke', 'uses' => 'JokeController@getJoke'));
Route::GET('/checkTitleDuplicity', 'JokeController@checkTitleDuplicity');
Route::GET('/checkTextDuplicity', 'JokeController@checkTextDuplicity');
Route::POST('/addVote', 'JokeController@addVote');
Route::GET('/{category}/{page?}',  array('as' => 'category', 'uses' => 'JokeController@getCategory'))->where('category', '[A-Z-a-z]+');
Route::GET('/{page?}',  array('as' => 'homepage', 'uses' => 'JokeController@getIndex'));