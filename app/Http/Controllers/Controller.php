<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models as Models;


abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    protected $currentUser;
    protected $role;
    protected $layout = 'app';

    //model returned to the view
    protected $model;

    protected $view;

    public function __construct(){

        $this->middleware('cas');

        $this->currentUser =$_SERVER["HTTP_CAS_USER"];

        // Layout - pass data for the partial views in the layout
        $this->renderNavigation();

    }


    private  function renderNavigation(){

        $user = Models\AppAdmin::find($this->currentUser);
        $is_admin= isset($user)?true:false;

        return \View::share("navigation",
            array('isAdmin'=>
                $is_admin));

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

}
