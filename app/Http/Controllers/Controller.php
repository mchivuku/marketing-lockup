<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models as Models;
use App\Services as Services;

require_once app_path()."/Services/LDAP/LDAPService.php";


abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    protected $currentUser;
    protected $isAdmin;
    protected $layout = 'app';
    protected $ldapService;

    //model returned to the view
    protected $model;

    protected $view;

    public function __construct(){

        $this->middleware('cas');

        $this->currentUser =$_SERVER["HTTP_CAS_USER"];

        $user = Models\AppAdmin::where('username','=',$this->currentUser)->first();
        $this->isAdmin = isset($user)?true:false;


        // Service
        $this->ldapService=new Services\LDAPService();


        // Layout - pass data for the partial views in the layout
        $this->renderNavigation();



    }



    private  function renderNavigation(){

        return \View::share("navigation",
            array('isAdmin'=>
                $this->isAdmin));

    }

    private function render()
    {
        // render the view
        return \View::make($this->layout)
            ->nest('content',
                $this->view,array('model'=>$this->model));

    }

    protected function title($title)
    {
        \View::share(array('title' => $title));
        return $this->render();
    }

    protected function view($layoutName)
    {
        $this->view = $layoutName;
        return $this;

    }


    protected function model($model)
    {
        $this->model = $model;
        return $this;
    }


    /*** HELPERS **/
    /***
     * Function to return ldap object
     * @param $username
     * @return \StdClass
     */
    public function construct_ldap_object($username){

        $p  = new \StdClass;
        $p->username = $username;

        $p->firstName = $this->ldapService->getFirstName($username);
        $p->lastName = $this->ldapService->getLastName($username);
        $p->email = $this->ldapService->getEmail($username);
        $p->name = sprintf("%s, %s",$p->lastName,$p->firstName);

        return $p;

    }

    /** Methods to insert flash messages */
    public function warning($message){
        $obj = new \StdClass;
        $obj->type = Models\ViewModels\Alerts::WARNING;
        $obj->message = $message;

        \Session::put('flash-message',$obj);


    }

    public function error($message){
        $obj = new \StdClass;
        $obj->type = Models\ViewModels\Alerts::ALERT;
        $obj->message = $message;

        \Session::set('flash-message',$obj);
    }


    public function success($message){
        $obj = new \StdClass;
        $obj->type = Models\ViewModels\Alerts::SUCCESS;
        $obj->message = $message;

        \Session::put('flash-message',$obj);
    }


    public function information($message){
        $obj = new \StdClass;
        $obj->type = Models\ViewModels\Alerts::INFORMATION;
        $obj->message = $message;

        \Session::put('flash-message',$obj);
    }


    public function secondary($message){
        $obj = new \StdClass;
        $obj->type = Models\ViewModels\Alerts::SECONDARY;
        $obj->message = $message;

        \Session::put('flash-message',$obj);
    }
}
