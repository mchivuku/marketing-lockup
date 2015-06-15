<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');


Route::get('/signatures', 'SignaturesController@index');
Route::get('/signatures/create', 'SignaturesController@create');
Route::post('/signatures/save', 'SignaturesController@save');
Route::post("signatures/approve/{id}/{comment?}", 'SignaturesController@approve');
Route::post("/signatures/approve/{id}/{comment?}", 'SignaturesController@approve');
Route::post("/signatures/deny/{id}/{comment?}", 'SignaturesController@deny');



Route::group(array('prefix' => 'users'), function () {

    Route::get('/', 'UsersController@index');

    Route::get('/search', 'UsersController@search');


    Route::get('/get', 'UsersController@get');
    Route::get('/edit', 'UsersController@edit');
    Route::post('/save', 'UsersController@save');
    Route::get('/save', 'UsersController@save');
    Route::post('/searchResults', 'UsersController@searchResults');


});
