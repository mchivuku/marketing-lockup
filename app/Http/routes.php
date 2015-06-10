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

Route::get('/users', 'UsersController@index');
Route::get('/signatures', 'SignaturesController@index');
Route::get('/signatures/create', 'SignaturesController@create');
Route::post('/signatures/save', 'SignaturesController@save');
Route::post("signatures/approve/{id}/{comment?}", 'SignaturesController@approve');
Route::post("/signatures/approve/{id}/{comment?}", 'SignaturesController@approve');
Route::post("/signatures/deny/{id}/{comment?}", 'SignaturesController@deny');
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
