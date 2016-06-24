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
use Illuminate\Auth\Access\UnauthorizedException;


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

        // check if the user is an administrator;
        $admin = Models\AppAdmin::where('username','=',$this->currentUser)->first();

        // TODO: exception handling with Laravel
        if(!isset($admin)) {
            $url = $_ENV['HOME_PATH']."/error/401.html";
            header('Location:'.$url);
        }

    }

    /**
     * Function to construct index page
     * @return mixed
     */
    public function index()
    {

        $inputs = \Input::all();


        if(isset($inputs['message'])) {
            if($inputs['type']==ViewModels\Alerts::ALERT)
                $this->error($inputs['message']);
            else
                $this->success($inputs['message']);
        }

        //Connect to the database ;
        $result = [];
        $admins = Models\AppAdmin::all();
        foreach($admins as $admin){
            $result[]=$this->construct_ldap_object($admin->username);
        }

        return $this->view('admin.admins')->model($result)
            ->pagePath('/tools/marketing-lockup/manage')
            ->sectionPath('/tools')
            ->title('Manage Administrators');

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
            $model->content= view('admin.viewadmin', array('model'=>$user_obj));
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

        $user = Models\AppAdmin::where('username','=',$inputs['username'])
            ->whereRaw('deleted_at is null')->first();

        if(isset($user)){
            return \Redirect::action('AdministratorsController@index',
                array('message'=>'User couldn\'t be added as
            the user already exists','type'=>ViewModels\Alerts::ALERT));

        }else{
            $user = new Models\AppAdmin();
            $user->username=$inputs['username'];
            $user->email = $inputs['email'];
            $user->save();
        }


        return \Redirect::action('AdministratorsController@index',
            array('message'=>'User has been added successfully',
                'type'=>ViewModels\Alerts::SUCCESS));

    }

    /***
     * Delete user - software
     * @return mixed
     */
    public function delete(){

        $inputs = \Input::all();
        Models\AppAdmin::where("username","=",$inputs['username'])->delete();

        return  \Redirect::action('AdministratorsController@index',
            array('message'=>'User was deleted successfully',
                'type'=>ViewModels\Alerts::SUCCESS));
    }


    /**
     * User search
     * @return \Illuminate\View\View
     */
    public function search(){
        $model = new Models\ViewModels\Modal();
        $model->title="User Search" ;
        $model->content= view('admin.addadmin',array('model'=>
            new ViewModels\ActiveDirectorySearchViewModel()));
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


        return  \View::make('admin.searchresults',array('model' => $results));

    }
}