<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 1:29 PM
 */


namespace App\Http\Controllers;

use App\Models as Models;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Validator;

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
            'text'=>'Create Marketing Lockup',
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

        $model->states = array_merge(array(array('id'=>0,'status'=>'All Lockups')),Models\ReviewStatus::all()
            ->toArray());
        $model->content = $this->getSignatures();

        $inputs = \Input::all();
        if(isset($inputs['message']))  $this->flash($inputs['message'],(isset($inputs['type'])
            ?$inputs['type']:Models\ViewModels\Alerts::SUCCESS));

        return $this->view('signatures')->model($model)->title('Manage Marketing Lockups');

    }


    public function getSignatures(){

        $inputs = \Input::all();
        $model = new \StdClass;

        // Admin
        if($this->isAdmin){

            $signatures =isset($inputs['status'])?
                Models\Signature::with('signaturereviews','reviewstatus')
                    ->where('statusId','=',$inputs['status'])->orderBy('updated_at','desc')->get():
                Models\Signature::with('signaturereviews','reviewstatus')->orderBy('updated_at','desc')->get();

            $CI = $this;
            foreach($signatures as $signature){
                //   $signature->preview = $signature->getSignatureThumbnail();
                $signature->preview='';
                $signature->name = isset($signature->fullName)?$signature->fullName:$signature->username;

                /** Next state for the required action link */
                if(stripos($signature->reviewstatus->status,'Pending')!==false){
                    $next_action_link =\URL::to(action('SignaturesController@review') . '?signatureid='
                        .$signature->signatureid );

                    $signature->nextAction="";
                   // if($this->currentUser==$signature->username){
                        $edit_link =\URL::to(action('SignaturesController@edit') . '?signatureid='
                            .$signature->signatureid );
                        $signature->nextAction.= "<a  href='$edit_link'>Edit</a> | ";
                   // }

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

            $signatures = isset($inputs['status'])? Models\Signature::with('signaturereviews','reviewstatus')
                ->where
                ('statusId','=', $inputs['status'])
                ->where('username','=',$this->currentUser)->orderBy('updated_at','desc')->get():
                Models\Signature::where('username',"=",$this->currentUser)->with('signaturereviews','reviewstatus')->orderBy('updated_at','desc')
                    ->get();

            $CI = $this;
            foreach($signatures as $signature){
                $signature->preview='';
                $obj =  $CI->construct_ldap_object($signature->username);
                $signature->name = sprintf("%s,%s",$obj->lastName,$obj->firstName);

                /** Next state for the required action link */
                if(stripos($signature->reviewstatus->status,'Pending')!==false){

                    $edit_link =\URL::to(action('SignaturesController@edit') . '?signatureid='
                        .$signature->signatureid );
                    $signature->nextAction.= "<a  href='$edit_link'>Edit</a> ";
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
        $inputs = \Input::all();


        if(isset($inputs['id'])){
            $signature = Models\Signature::where('signatureid','=',$inputs['id'])
                ->first()->getSignaturePreview();
            $model = new Models\ViewModels\Modal();

            $model->content= "<div class=\"panel\">

            <div class=\"panel-body\">
             $signature
                 </div>
                </div>";


            $model->title= 'Signature Preview';
            $model->setAttribute('id','viewModel');

            return view('modal',array('model'=>$model));
        }


        $signature = new Models\Signature();
        $signature->primaryText= $inputs['p'];;
        $signature->secondaryText=$inputs['s'];
        $signature->tertiaryText=$inputs['t'];
        $signature->named = $inputs['named'];
        $signature->campus = $inputs['campus'];


        return $signature->getSignaturePreview();

    }

    public function getThumbnail(){

        $inputs = \Input::all();
        $signature = Models\Signature::where('signatureid','=',$inputs['id'])->first();
        return $signature->getSignatureThumbnail();
    }

    public function getReviewComments(){
        $inputs = \Input::all();
        if(isset($inputs['signatureid'])){
            $signature =
                Models\Signature::with('signaturereviews','reviewstatus')
                    ->where('signatureid','=',$inputs['signatureid'])
                    ->first();
            $signature->signaturereviews->each(function($item)use($signature){
                if($signature->comments!="")
                    $signature->comments.="; ";
                $signature->comments.= $item->comments;
            });

            $model = new Models\ViewModels\Modal();

            $model->content= view('viewcomments', array('model'=>$signature->comments));
            $model->title= 'Review Comments';
            $model->setAttribute('id','viewModel');

            return view('modal',array('model'=>$model));
        }
    }

    /**
     * Create new signature
     * @return mixed
     */
    public function create(){

        $inputs = \Input::all();

        if(count($inputs)>0){
            //read all post variables
            $signature = new Models\Signature();
            $signature->named = $inputs['named'];
            $signature->primaryText = $inputs['p'];
            $signature->secondaryText = $inputs['s'];
            $signature->tertiaryText = $inputs['t'];
            $signature->campus = $inputs['campus'];

            if(isset($inputs['signatureid']))
                $signature->signatureid =$inputs['signatureid'];

            \View::share('editmode',true);
        }else{
            $signature = new Models\Signature();

            \View::share('editmode',false);
        }

        // send campuses that need to be treated differently during creation
        \View::share('allcampuses',Models\Campus::getAllCampuses());
        \View::share('iupuilikecampuses',Models\Campus::getIUPUILikeCampuses());



        return $this->view('addEditSignature')
            ->model($signature)->title('Create Marketing Lockup');
    }


    public function edit(){

        $id = \Input::get('signatureid');
        $signature  = Models\Signature::find($id);

        // help with back fill
        if(!isset($signature->campus))$signature->campus="IU";


        \View::share('editmode',true);

        // send campuses that need to be treated differently during creation
        \View::share('allcampuses',Models\Campus::getAllCampuses());
        \View::share('iupuilikecampuses',Models\Campus::getIUPUILikeCampuses());


        return $this->view('addEditSignature')->model($signature)->title('Edit Marketing Lockup');

    }

    public function confirm()
    {
        $inputs = \Input::all();


        //read all post variables
        $signature = new Models\Signature();
        $signature->named = $inputs['named'];
        $signature->primaryText = $inputs['p'];
        $signature->secondaryText = $inputs['s'];
        $signature->tertiaryText = $inputs['t'];
        $signature->campus = $inputs['campus'];



        if(isset($inputs['signatureid'])){
            $signature->signatureid =$inputs['signatureid'];

            $backUrl =
                link_to_action('SignaturesController@create', 'Back', array('p' => $inputs['p'],
                    's' => $inputs['s'], 't' => $inputs['t'], 'signatureid' => $inputs['signatureid'],
                    'named' => $inputs['named'],'campus'=>$inputs['campus']), array('class' => 'button invert'));

        }else{
            $backUrl =
                link_to_action('SignaturesController@create', 'Back', array('p' => $inputs['p'],
                    's' => $inputs['s'], 't' => $inputs['t'],
                    'named' => $inputs['named'],'campus'=>$inputs['campus']), array('class' => 'button invert'));
        }

        \View::share("backLink", array('backLink'=>$backUrl));
         return $this->view('confirmSignature')->model($signature)->title('Confirm Marketing Lockup');

    }



    /***
     * Save signature
     *
     * @return mixed
     */
    public function save(){

        $inputs = \Input::all();


        $message="";
        $user = $this->currentUser;
        $obj =  $this->construct_ldap_object($user);
        $name = sprintf("%s %s",$obj->firstName,$obj->lastName);


        if(isset($inputs['signatureid'])){
            $signature = Models\Signature::find(\Input::get('signatureid'));
            $review_status = Models\ReviewStatus::where('status','like','pending%')->first();

            \DB::transaction(function()use($signature,$user,$inputs,$name,$review_status)
            {
                $timestamp = $this->getcurrentTimestamp();

                //$signature->username =  $user;
                //$signature->fullName=$name;
                $signature->primaryText =  $inputs['p'];
                $signature->secondaryText =   $inputs['s'];
                $signature->tertiaryText =   $inputs['t'];
                $signature->campus = $inputs['campus'];
                $signature->updated_at = $timestamp;
                $signature->named = $inputs['named'];
                $signature->updated_by = $name;

                $signatureReview = new Models\SignatureReview();
                $signatureReview->reviewstatus = $review_status->id;
                $signatureReview->created_at =$timestamp;
                $signatureReview->updated_at = $timestamp;
                $signatureReview->signatureid = $signature->signatureid;
                $signatureReview->updated_by = $name;
                $signatureReview->save();
                $signature->save();

            });
            $message = "Lockup was updated successfully";

        }
        else{

            // Create new signature
            $signature = new Models\Signature();
            $review_status = Models\ReviewStatus::where('status','like','pending%')->first();

            \DB::transaction(function()use($signature,$user,$inputs,$review_status,$name)
            {
                $timestamp = $this->getcurrentTimestamp();

                $signature->username =  $user;
                $signature->fullName=$name;
                $signature->primaryText =  $inputs['p'];
                $signature->secondaryText =   $inputs['s'];
                $signature->tertiaryText =   $inputs['t'];
                $signature->named = $inputs['named'];
                $signature->campus = $inputs['campus'];


                $signature->created_at = $timestamp;
                $signature->updated_at = $timestamp;
                $signature->statusId = $review_status->id;
                $signature->updated_by = $name;


                $signature->save();

                $signatureReview = new Models\SignatureReview();
                $signatureReview->reviewstatus = $review_status->id;
                $signatureReview->created_at =$timestamp;
                $signatureReview->updated_at = $timestamp;
                $signatureReview->signatureid = $signature->signatureid;
                $signatureReview->updated_by = $name;

                $signatureReview->save();


            });
            $message = "Lockup was created successfully";
        }




        return \Redirect::action('SignaturesController@index',array('message'=>$message,
            'type'=>Models\ViewModels\Alerts::SUCCESS));



    }

    public function review(){

        $signatureId = \Input::get('signatureid');

        $model = new \StdClass;

        Models\ReviewStatus::where('status','not like','pending%')->get()->each(
            function
            ($item)use($model){
                if(stripos($item->status,'approve')!==false){
                    $item->buttonText = "Build & Approve";

                }else{
                    $item->buttonText = $item->action;

                }

                $model->statuses[]=$item;
            });

        $model->signatureid = $signatureId;
        $model->signature  =  Models\Signature::where('signatureid','=',$signatureId)->first();

        return $this->view('signature-review')->model($model)->title('Review Marketing Lockup');

    }

    /***
     * Method gets called on approve
     * @return mixed
     */
    public function approve(){
        $inputs =  \Input::all();

        $signature = Models\Signature::find($inputs['signatureid']);

        //check if the signature is approved.
        $approved_id = Models\ReviewStatus::where('status','like','approve%')->first()->id;
        if($signature->statusId == $approved_id){
            return json_encode(array('status'=>false,'message'=>'Lockup is already approved'));
        }

        $return = $signature->build();

        if(isset($return)){

            $signature->downloadPath = $return;

            $review_status = Models\ReviewStatus::where('action','like','approve%')->first();

            /** Transaction to implement all the changes in one go */
            $id = $this->saveSignatureReview($review_status,$signature,$inputs);

            $data =  isset($inputs['comment'])?$inputs['comment']:'';
            $d = array("data" => $data,'admin'=>\Config::get('mail.from'));

            $obj  = $this->construct_ldap_object($signature->username);
            \Mail::send('emails.approve', $d, function($message)use($obj)
            {
                $message->to($obj->email, $obj->name)->subject('Marketing Lockup Approved!');
            });

            //update that email has been sent;
            $review = Models\SignatureReview::find($id);
            $review->emailsent = 1;
            $review->save();
            $signature->save();

            return json_encode(array('status'=>true,'message'=>'Successfully completed the build/approval process'));
        }

        return  json_encode(array('status'=>false,'message'=>'Failed to create the lockup'));

    }

    /***
     * Method gets called when a signature is denied
     * @return mixed
     */
    public function denied(){
        $inputs =  \Input::all();

        $signature = Models\Signature::find($inputs['signatureid']);
        $review_status = Models\ReviewStatus::where('action','like','denied%')->first();


        if($signature->statusId == $review_status->id){
            return json_encode(array('status'=>false,'message'=>'Marketing Lockup is already denied'));
        }


        /** Transaction to implement all the changes in one go */
        if(isset($signature))
            $id = $this->saveSignatureReview($review_status,$signature,$inputs);

        $data = isset($inputs['comment'])?$inputs['comment']:'';
        $d = array("data" => $data);

        $obj  = $this->construct_ldap_object($signature->username);
        \Mail::send('emails.denied', $d, function($message)use($obj)
        {
            $message->to($obj->email, $obj->name)->subject('Marketing Lockup Request Denied!');
        });


        //update that email has been sent;
        $review = Models\SignatureReview::find($id);
        $review->emailsent = 1;
        $review->save();

        return json_encode(array('status'=>true,'message'=>'success'));

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
            $signatureReview->signatureid = $signature->signatureid;

            $signatureReview->save();

            $signature->updated_at = $timestamp;
            $signature->statusId = $review_status->id;

            $signature->save();

        });

        return $signatureReview->id;
    }



    function getDownload(){
        $inputs = \Input::all();

        $signature = Models\Signature::where('signatureid','=',$inputs['signatureid'])->first();
        $downloadpath = $signature->downloadPath;

        $headers = array(
            'Content-Type: application/zip',
            'Content-Length: '. filesize($downloadpath)
        );
        $filename = basename($downloadpath);

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Length: " .filesize($downloadpath));

        readfile($downloadpath);
        exit;

    }



}