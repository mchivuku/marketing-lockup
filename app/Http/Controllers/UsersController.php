<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/26/15
 * Time: 8:47 AM
 */

namespace App\Http\Controllers;
use DB;
use App\Models;

class UsersController extends Controller{



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cas');
    }

    public function index()
    {
        //Connect to the database ;
        //$dbname = DB::connection()->getDatabaseName();
        $users = Models\AppUser::all();
        $data = array('name' => "Shanmukh" , 'usersCollection' => $users);
        return view('users') -> with('dt' , $data);
    }

}