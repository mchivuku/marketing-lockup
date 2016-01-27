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



    Route::get('/createStepOne', array('as'=>'createStepOne','uses'=>'SignaturesController@createStepOne'));

    Route::get('/create', array('as'=>'create','uses'=>'SignaturesController@create'));
    Route::get('/edit', array('as'=>'edit','uses'=>'SignaturesController@edit'));
    Route::get('/getSignatures', array('as'=>'signature-table',
        'uses'=>'SignaturesController@getSignatures'));

    //Form Toggle - AddEditSignature
    Route::get('/namedschool',function(){
        $inputs = \Input::all();

        $data = array('primaryText'=>$inputs['p'],'secondaryText'=>$inputs['s'],'tertiaryText'=>$inputs['t']);

        return View::make("includes.named-school-form",$data);

    });

    Route::get('/iupuiform',function(){
        $inputs = \Input::all();


        if(!isset($inputs['p']))
            $inputs['p']='';
        if(!isset($inputs['s']))
            $inputs['s']='';
        if(!isset($inputs['t']))
            $inputs['t']='';

        if(!isset($inputs['campus']))
            $inputs['campus']="";

        if(!isset($inputs['named']))
            $inputs['named']=0;

        return View::make("includes/iupui-like-addEditSignature", array('primaryText'=>$inputs['p'],'secondaryText'=>$inputs['s'],'tertiaryText'=>$inputs['t'],
            'named'=>$inputs['named'],'campus'=>$inputs['campus']));

    });

    Route::get('/allcampusform',function(){
        $inputs = \Input::all();

        if(!isset($inputs['p']))
            $inputs['p']='';
        if(!isset($inputs['s']))
            $inputs['s']='';
        if(!isset($inputs['t']))
            $inputs['t']='';

        if(!isset($inputs['named']))
            $inputs['named']=1;

        return View::make("includes.allcampus-addEditSignature", array('primaryText'=>$inputs['p'],'secondaryText'=>$inputs['s'],'tertiaryText'=>$inputs['t'],
            'named'=>$inputs['named']));
        //return view("includes/allcampus-addEditSignature",
        // );
    });

    Route::get('/allschool',function(){
        $inputs = \Input::all();

        if(!isset($inputs['named']))
            $inputs['named']=0;

        return view("includes/non-named-school-form", array('primaryText'=>$inputs['p'],'secondaryText'=>$inputs['s'],
            'tertiaryText'=>$inputs['t']));
    });

    Route::get('/getPreview', array('as'=>'preview','uses'=>'SignaturesController@getPreview'));
    Route::get('/getThumbnail', array('as'=>'thumbnail','uses'=>'SignaturesController@getThumbnail'));

    Route::get('/getReviewComments', array('as'=>'reviewcomments','uses'=>'SignaturesController@getReviewComments'));
    Route::get('/review', array('as'=>'signaturereview','uses'=>'SignaturesController@review'));


    Route::post('/confirmsignature', array('as'=>'confirm','uses'=>'SignaturesController@confirm'));
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
