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
    Route::get('/edit', array('as'=>'edit','uses'=>'SignaturesController@edit'));
    Route::get('/getSignatures', array('as'=>'signature-table',
        'uses'=>'SignaturesController@getSignatures'));

    Route::get('/namedschoolimages',function(){
        return view("named-school-image-examples");
    });
    Route::get('/allschoolimages',function(){
        return view("all-school-image-examples");
    });


    Route::get('/getPreview', array('as'=>'preview','uses'=>'SignaturesController@getPreview'));

    Route::get('/review', array('as'=>'signaturereview','uses'=>'SignaturesController@review'));

    Route::post('/savesignature', array('as'=>'save','uses'=>'SignaturesController@save'));


    Route::get("/approve", 'SignaturesController@approve');
    Route::get("/denied", 'SignaturesController@denied');
    Route::get("/download", 'SignaturesController@getDownload');


});


Route::group(array('prefix' => 'admin'), function () {

    Route::get('/', array('as'=>'admins',
        'uses'=>'AdministratorsController@index'));
    Route::get('/search', 'AdministratorsController@search');
    Route::get('/get', 'AdministratorsController@get');
    Route::get('/delete', 'AdministratorsController@delete');
    Route::post('/save', 'AdministratorsController@save');
    Route::get('/save', 'AdministratorsController@save');
    Route::post('/searchResults', 'AdministratorsController@searchResults');

});

Route::group(array('prefix' => 'emaillockup'), function () {

    Route::get('/', array('as'=>'emaillockup',
        'uses'=>'EmailLockUpController@index'));


});
