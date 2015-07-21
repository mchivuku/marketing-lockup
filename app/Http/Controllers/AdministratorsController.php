<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/26/15
 * Time: 8:47 AM
 */

namespace App\Http\Controllers;

use App\Models as Models;
use App\Models\ViewModels as ViewModels;


/**
 * Class AdministratorsController
 * @package App\Http\Controllers
 */
class AdministratorsController extends Controller{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       parent::__construct();


    }

    /**
     * Function to construct index page
     * @return mixed
     */
    public function index()
    {
        //Connect to the database ;
        $result = [];
        $admins = Models\AppAdmin::all();
        foreach($admins as $admin){
            $result[]=$this->construct_ldap_object($admin->username);
        }

        return $this->view('admins')->model($result)->title('Manage Administrators');

    }



    /**
     * Function to get information of the user
     * @return mixed
     */
    public function get(){
        $username = \Input::get('username');

        $user = Models\AppAdmin::where('username','=',$username)->first();

        $model = new Models\ViewModels\Modal();

        if(isset($user)){
            $user_obj = $this->construct_ldap_object($username);
            $model->content= view('viewadmin', array('model'=>$user_obj));
            $model->title= sprintf("%s,%s",$user_obj->firstName,$user_obj->lastName);

        }else{
            $model->title= 'Not Found';
            $model->content='Administrator was not found.';
        }
        $model->setAttribute('id','viewModel');

        return view('modal',array('model'=>$model));

    }


    /**
     * Save user information
     * @return mixed
     */
    public function save(){

        $inputs = \Input::all();

        $user = Models\AppAdmin::where('username','=',$inputs['username'])->first();

        if(isset($user)){
            \Session::flash('flash-message', 'User couldn\'t be added as the user already exists');

        }else{

            $user = new Models\AppAdmin();
            $user->username=$inputs['username'];
            $user->email = $inputs['email'];

            $user->save();
        }

        return  \Redirect::action('AdministratorsController@index');
    }


    /***
     * Delete user - software
     * @return mixed
     */
    public function delete(){

        $inputs = \Input::all();
        Models\AppAdmin::where("username","=",$inputs['username'])->delete();

        return  \Redirect::action('AdministratorsController@index');
    }


    /**
     * User search
     * @return \Illuminate\View\View
     */
    public function search(){
        $model = new Models\ViewModels\Modal();
        $model->title="User Search" ;
        $model->content= view('addadmin',array('model'=> new ViewModels\ActiveDirectorySearchViewModel()));
        $model->setAttribute('id','userSearch');
        return view('modal',array('model'=>$model));
    }

    /**
     * Get LDAP user search results
     * @return mixed
     */
    public function searchResults(){
        $inputs = \Input::all();

        $read_input = function($name)use($inputs){
            return  isset($inputs[$name])?$inputs[$name]:'';
        };

        $results = $this->ldapService->search(array('username'=>$read_input('username'),
            'firstName'=>$read_input('firstName'),
            'lastName'=>$read_input('lastName')));

        array_walk($results, function(&$user){
            $params = array('firstName' => $user['firstName'], 'lastName' => $user['lastName'],
                'username'=>$user['username'],'email'=>$user['email']);

            $link=\URL::to(action('AdministratorsController@save') . '?' . http_build_query($params));
            $user['addLink'] =  "<a  class='button tiny round' href='$link'>Add Admin</a>";

        });
        return  \View::make('searchresults',array('model' => $results));

    }
}