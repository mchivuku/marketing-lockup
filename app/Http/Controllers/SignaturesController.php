<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 1:29 PM
 */


namespace App\Http\Controllers;

use App\Models as Models;

class SignaturesController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Signature Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {

        parent::__construct();
        $this->leftnavigation();

    }

    public function leftnavigation(){
        $navigation = array(array('route_name'=>'signatures.create','text'=>'Create Signature',
            'url'=>\URL::to(action('SignaturesController@create'))));
        \View::share('leftnavigation',$navigation);
    }


    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        // get all signatures or get user signature - based on the user role.
        $user = Models\AppAdmin::find($this->currentUser);

        // Get only those signatures that are active
        $review_status_query = \DB::table('signatureReview')
            ->join('reviewStatus','reviewStatus.id','=','signatureReview.reviewstatus')
            ->whereRaw('isActive = 1')
            ->select(array('signatureid','status','reviewedby','created_at','updated_at','signatureReview.id',
                'emailsent','comments'))->toSql();


        // Admin
        if(isset($user)){

            $signatures = \DB::table('signature')->join(\DB::Raw("($review_status_query) as s"),"s.signatureid","=",
                "signature.signatureid")
                ->select(array('primaryText', 'secondaryText', 'tertiaryText', 'type', 'username','s.comments',
                    's.emailsent','s.status','s.reviewedby','s.created_at','s.updated_at','s.id'
                ))->get();

         }else{
             $signatures = \DB::table('signature')->join(\DB::Raw("($review_status_query) as s"),"s.signatureid","=",
                "signature.signatureid")->where('username','=',$this->currentUser)
                ->select(array('primaryText', 'secondaryText', 'tertiaryText', 'type', 'username','s.comments',
                    's.emailsent','s.status','s.reviewedby','s.created_at','s.updated_at','s.id'
                ))->get();

       }

        // build array - view
        array_walk($signatures, function()use($user){



        });

        return $this->view('signatures')->model($signatures)->title('Manage Signatures');
    }


    //Method to return json view for previewing signature.
    public function getSignaturePreview(){
            $input = \Input::all();

            $primary=$input['primary'];
            $secondary  = $input['secondary'];
            $tertiary = $input['tertiary'];
            $v = $input['v'];

            $signature = new Models\Signature();
            $signature->primaryText = $primary;
            $signature->secondaryText = $secondary;
            $signature->tertiaryText = $tertiary;
            $signature->type  = $v;

            return  \View::make('preview',array('model' => $signature->getSignaturePreview()));
    }


    public function create(){
        return $this->view('createsignature')->title('Create Signature');
    }


    public function edit(){


    }

    //TODO: add validation - on input
    public function save(){

        $signature = new Models\Signature();

        $signature->create(array(
            'username'          => $_SERVER["HTTP_CAS_USER"],
            'primaryText'             => \Input::get('p'),
            'secondaryText'          => \Input::get('s'),
            'tertiaryText'          => \Input::get('t'),
            'type'              =>\Input::get('hdnsignaturetype')
        ));

        $signature_id =  \DB::getPdo()->lastInsertId();

        // insert record into signature review table.
        if(isset($signature_id)){
            $review = new Models\SignatureReview();

            $review_status = Models\ReviewStatus::where('status','like','pending%')->first();

            $timestamp = date('Y-m-d H:i:s');

            $review->create(array(
                    'signatureid'     => $signature_id,
                    'reviewStatus'    => $review_status->id,
                    'isActive'    =>  '1',
                    'created_at'  =>$timestamp,
                    'updated_at' =>$timestamp
                ));

            return \Redirect::route('signatures');
        }


    }
}