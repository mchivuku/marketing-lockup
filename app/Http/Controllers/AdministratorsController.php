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

class AdministratorsController extends Controller{

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

    private function construct_admin_object($username){

        $p  = new \StdClass;
        $p->username = $username;

        $p->firstName = $this->ldapService->getFirstName($username);
        $p->lastName = $this->ldapService->getLastName($username);
        $p->email = $this->ldapService->getEmail($username);

        return $p;

    }
    public function index()
    {
        //Connect to the database ;
        $result = array();
        $admins = Models\AppAdmin::all();
        foreach($admins as $admin){

            $result[]=$this->construct_admin_object($admin->username);
        }

        return $this->view('admins')->model($result)->title('Manage Administrators');

    }



    // Get User
    public function get(){
        $username = \Input::get('username');

        $user = Models\AppAdmin::where('username','=',$username)->first();

        $model = new Models\ViewModels\Modal();

        if(isset($user)){
            $user_obj = $this->construct_admin_object($username);
            $model->content= view('viewadmin', array('model'=>$user_obj));
            $model->title= sprintf("%s,%s",$user_obj->firstName,$user_obj->lastName);

        }else{
            $model->title= 'Not Found';

            $model->content='Administrator was not found.';
        }


        $model->setAttribute('id','viewModel');

         return view('modal',array('model'=>$model));

    }


    // save user function for both 'add/edit' - TODO fix flash
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

            $link=\URL::to(action('AdministratorsController@save') . '?' . http_build_query($params));
            $user['addLink'] =  "<a  class='btn btn-sm btn-primary' href='$link'>Add Admin</a>";


        });

        return  \View::make('searchresults',array('model' => $results));

    }
}