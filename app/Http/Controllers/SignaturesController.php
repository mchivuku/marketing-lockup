<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 1:29 PM
 */


namespace App\Http\Controllers;
date_default_timezone_set('UTC');

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
        $navigation = array(array('route_name'=>'signatures.create',
            'text'=>'Create Signature',
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
         $model =new \StdClass;

         $model->states = array_merge(array(array('id'=>0,'status'=>'All Signatures')),Models\ReviewStatus::all()
           ->toArray());
         $model->content = $this->getSignatures();
         return $this->view('signatures')->model($model)->title('Manage Signatures');

    }


    public function getSignatures(){

        $inputs = \Input::all();
        $model = new \StdClass;

        // Admin
        if($this->isAdmin){

            $signatures =isset($inputs['status'])? Models\Signature::where('statusId','=',$inputs['status'])->get():
                Models\Signature::all();

            $CI = $this;

            foreach($signatures as &$signature){
                $signature->preview = $signature->getSignatureThumbnail();
                $obj =  $CI->construct_ldap_object($signature->username);
                $signature->name = sprintf("%s,%s",$obj->lastName,$obj->firstName);

                /** Next state for the required action link */
                if(stripos($signature->reviewstatus->status,'Pending')!==false){
                    $next_action_link =\URL::to(action('SignaturesController@review') . '?signatureid='
                        .$signature->signatureid );

                    $signature->nextAction="";
                    if($this->currentUser==$signature->username){
                        $edit_link =\URL::to(action('SignaturesController@edit') . '?signatureid='
                            .$signature->signatureid );
                        $signature->nextAction.= "<a  href='$edit_link'>Edit</a> | ";
                    }

                    $link_name = 'Approve/Deny';
                    $signature->nextAction .="<a  href='$next_action_link'>$link_name</a>";


                }else{

                    $signature->nextAction = $signature->reviewstatus->status;

                }

                $signature->signaturereviews->each(function($item)use($signature){
                    if($signature->comments!="")
                        $signature->comments.="; ";
                    $signature->comments.= $item->comments;
                });

            }
        }
        else{

            $signatures =isset($inputs['status'])? Models\Signature::where('statusId','=',$inputs['status'])
                ->where('username','=',$this->currentUser)->get():
                Models\Signature::where('username','=',$this->currentUser);

            foreach($signatures as &$signature){
                $signature->preview = $signature->getSignatureThumbnail();
                $obj =  $this->construct_ldap_object($signature->username);
                $signature->name = sprintf("%s,%s",$obj->lastName,$obj->firstName);

                /** Next state for the required action link */
                if(stripos($signature->reviewstatus->status,'Pending')!==false){
                    $next_action_link =\URL::to(action('SignaturesController@review') . '?signatureid='
                        .$signature->signatureid );

                    $signature->nextAction="";
                    if($this->currentUser==$signature->username){
                        $edit_link =\URL::to(action('SignaturesController@edit') . '?signatureid='
                            .$signature->signatureid );
                        $signature->nextAction.= "<a  href='$edit_link'>Edit</a> &nbsp;| &nbsp;";
                    }

                    $link_name = 'Approve/Deny';
                    $signature->nextAction .="<a  href='$next_action_link'>$link_name</a>";

                }else{

                    $signature->nextAction =$signature->reviewstatus->status;
                }

            }


        }

        $model->signatures =  $signatures;

        $model->currentUser = $this->currentUser;
        $model->isAdmin = $this->isAdmin;

        return view('signature-table',array('model'=>$model));
    }


    /**
     * Method to return json view of the signature preview
     * @return \Illuminate\View\View
     */
    public function getPreview(){
        $input = \Input::all();
        $id = $input['id'];

        $signature = Models\Signature::where('signatureid','=',$id)->first()->getSignaturePreview();


        $model = new Models\ViewModels\Modal();

        $model->content=   $signature ;
        $model->title= 'Signature Preview';
        $model->setAttribute('id','viewModel');

        return view('modal',array('model'=>$model));
    }

    public function create(){
        $signature = new Models\Signature();
        $signature->primaryText='Primary';
        $signature->secondaryText='Secondary';
        $signature->tertiaryText='Tertiary';

        return $this->view('addEditSignature')->model($signature)->title('Create Signature');
    }


    public function edit(){
        $id = \Input::get('id');
        $signature  = Models\Signature::find($id);
        return $this->view('addEditSignature')->model($signature)->title('Edit Signature');

    }




    /***
     * Save signature
     *
     * @return mixed
     */
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


            $timestamp = $this->getcurrentTimestamp();

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


    public function review(){
        $signatureId = \Input::get('signatureid');

        $model = new \StdClass;
        $model->statuses = Models\ReviewStatus::where('status','not like','pending%')->get();
        $model->signatureid = $signatureId;

        return $this->view('signature-review')->model($model)->title('Review Signature');

    }

    /***
     * Method gets called on approve
     * @return mixed
     */
    public function approve(){
        $inputs =  \Input::all();

        $signature = Models\Signature::find($inputs['signatureid']);
        $review_status = Models\ReviewStatus::where('action','like','approve%')->first();

        /** Transaction to implement all the changes in one go */
        $id = $this->saveSignatureReview($review_status,$signature,$inputs);


        $data = $inputs['comment'];
        $d = array("data" => $data);

        $obj  = $this->construct_ldap_object($signature->username);
        \Mail::send('emails.approve', $d, function($message)use($obj)
        {
            $message->to($obj->email, $obj->name)->subject('Signature Approved!');
        });


        //update that email has been sent;
        $review = Models\SignatureReview::find($id);
        $review->emailsent = 1;
        $review->save();


        return \Redirect::action('SignaturesController@index');

    }

    /***
     * Method gets called when a signature is denied
     * @return mixed
     */
    public function deny(){
        $inputs =  \Input::all();

        $signature = Models\Signature::find($inputs['signatureid']);
        $review_status = Models\ReviewStatus::where('action','like','denied%')->first();

        /** Transaction to implement all the changes in one go */
        $id = $this->saveSignatureReview($review_status,$signature,$inputs);


        $data = $inputs['comment'];
        $d = array("data" => $data);

        $obj  = $this->construct_ldap_object($signature->username);
        \Mail::send('emails.deny', $d, function($message)use($obj)
        {
            $message->to($obj->email, $obj->name)->subject('Signature Request Denied!');
        });


        //update that email has been sent;
        $review = Models\SignatureReview::find($id);
        $review->emailsent = 1;
        $review->save();


        return \Redirect::action('SignaturesController@index');

    }

    /**** Helper methods ****/

    /***
     * Helper method to retrieve the current timestamp
     *
     * @return int|string
     */
    private function getcurrentTimestamp(){
        $tz = 'America/Indiana/Indianapolis';
        $timestamp = time();
        $dt = new \DateTime("now", new \DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp

        $timestamp = $dt->format('Y-m-d H:i:s');
        return $timestamp;
    }

    /**
     * private helper method that saves a signature review for a signature
     * @param $review_status
     * @param $signature
     * @param $inputs
     * @return mixed
     */
    private function saveSignatureReview($review_status,$signature, $inputs){
        $user = $this->currentUser;
        $timestamp = $this->getcurrentTimestamp();

        $signatureReview = new Models\SignatureReview();
        \DB::transaction(function()use($signature,$review_status,$timestamp,$user,$inputs,$signatureReview){

            $signatureReview->reviewstatus = $review_status->id;
            $signatureReview->reviewedby = $user;
            $signatureReview->comments =  $inputs['comment'];
            $signatureReview->created_at =$timestamp;
            $signatureReview->updated_at = $timestamp;
            $signatureReview->signatureid = $signature->id;

            $signatureReview->save();

            $signature->updated_at = $timestamp;
            $signature->statusId = $review_status->signatureid;

            $signature->save();

        });

        return $signatureReview->id;
    }

}