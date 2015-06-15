<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/26/15
 * Time: 8:47 AM
 */

namespace App\Http\Controllers;


use DB;
use App\Models as Models;
use App\Models\ViewModels as ViewModels;
use App\Services as Services;

require_once app_path()."/Services/LDAP/LDAPService.php";

class UsersController extends Controller{

    protected $ldapService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       parent::__construct();

       // Service

        $this->ldapService=new Services\LDAPService();

    }

    public function index()
    {
        //Connect to the database ;
        $users = Models\AppUser::all();
        return $this->view('users')->model($users)->title('Manage Users');

    }

    // Get User
    public function get(){
        $username = \Input::get('username');

        $user = Models\AppUser::where('username','=',$username)->first();

        $model = new Models\ViewModels\Modal();
        $model->title= sprintf("%s,%s",$user->firstName,$user->lastName);

        $model->content= view('user',array('model'=>$user));
        $model->setAttribute('id','viewModel');


        return view('modal',array('model'=>$model));

    }

    // Edit User
    public function edit(){

        $username = \Input::get('username');
        $user = Models\AppUser::where('username','=',$username)->first();

        // get user and all the roles
        $userrole = new Models\ViewModels\UserRole();
        $userrole->user= $user;
        $userrole->roles =  Models\Role::all();

        $model = new Models\ViewModels\Modal();
        $model->title="Edit User - " .$user->getName();
        $model->content= view('editUser',array('model'=>$userrole));
        $model->setAttribute('id','editModal');


        return view('modal',array('model'=>$model));
    }

    // save user function for both 'add/edit'
    public function save(){

        $inputs = \Input::all();

        $user = Models\AppUser::where('username','=',$inputs['username'])->first();

        //EDIT
        if(isset($user)){
            $user->roleId=$inputs['role'];
        }
        //ADD
        else{
            $user = new Models\AppUser();

            $user->firstName=$inputs['firstName'];
            $user->lastName=$inputs['lastName'];
            $user->username=$inputs['username'];
            $user->email = $inputs['email'];

        }

        $user->save();
        return  \Redirect::action('UsersController@index');
    }


    /**
     * User search
     * @return \Illuminate\View\View
     */
    public function search(){
        $model = new Models\ViewModels\Modal();
        $model->title="User Search" ;
        $model->content= view('addnewuser',array('model'=> new ViewModels\ActiveDirectorySearchViewModel()));
        $model->setAttribute('id','userSearch');
        return view('modal',array('model'=>$model));
    }

    /*
     *
     * User Search Results
     *
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

            $link=\URL::to(action('UsersController@save') . '?' . http_build_query($params));
            $user['addLink'] =  "<a  class='btn btn-sm btn-primary' href='$link'>Add user</a>";


        });

        return  \View::make('usersearchresults',
            array('model' => $results));

    }
}