<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 5/15/16
 * Time: 7:44 AM
 */

namespace App\Http\Controllers;

use App\Models as Models;
use App\Models\ViewModels as ViewModels;
use Illuminate\Auth\Access\UnauthorizedException;

/**
 * Class AdministratorsController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();

        // check if the user is an administrator;
        $admin = Models\AppAdmin::where('username', '=', $this->currentUser)->first();

        // TODO: exception handling with Laravel
        if (!isset($admin)) {
            $url = $_ENV['HOME_PATH'] . "/error/401.html";
            header('Location:' . $url);
        }

    }


    /**
     * Function to construct index page
     * @return mixed
     */
    public function index()
    {

         return $this->view('admin.index')
             ->pagePath('/tools/marketing-lockup/manage')
             ->sectionPath('/tools')
            ->title('Admin');

    }
}