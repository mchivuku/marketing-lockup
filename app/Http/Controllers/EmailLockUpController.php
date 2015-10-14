<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/14/15
 * Time: 3:52 PM
 */

namespace App\Http\Controllers;

use App\Models as Models;
use App\Models\ViewModels as ViewModels;


/**
 * Class AdministratorsController
 * @package App\Http\Controllers
 */
class EmailLockUpController extends Controller{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function index(){

        return $this->view('create-email-lockup')->title('Create Email Signature');
    }


}