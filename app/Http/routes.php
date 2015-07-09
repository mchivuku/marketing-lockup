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


Route::group(array('prefix'=>'signatures'),function(){

    Route::get('/', array('as'=>'signatures',
             'uses'=>'SignaturesController@index'));

    Route::get('/create', array('as'=>'create','uses'=>'SignaturesController@create'));

    Route::post('/savesignature', array('as'=>'save','uses'=>'SignaturesController@save'));
    Route::post('/approve/{id}/{comment?}', array('as'=>'save','uses'=>'SignaturesController@save'));


    Route::post("signatures", 'SignaturesController@approve');
    Route::post("/signatures/approve/{id}/{comment?}", 'SignaturesController@approve');
    Route::post("/signatures/deny/{id}/{comment?}", 'SignaturesController@deny');


});


Route::group(array('prefix' => 'admin'), function () {

    Route::get('/', 'AdministratorsController@index');

    Route::get('/search', 'AdministratorsController@search');

    Route::get('/get', 'AdministratorsController@get');
    Route::get('/delete', 'AdministratorsController@delete');
    Route::post('/save', 'AdministratorsController@save');
    Route::get('/save', 'AdministratorsController@save');
    Route::post('/searchResults', 'AdministratorsController@searchResults');


});
