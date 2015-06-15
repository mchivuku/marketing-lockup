<?php namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Review;
use App\Models\Signature;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class DeleteSignaturesController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Oink Controller
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
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        //Get all the signatures created by the user.
        //if the user is an admin, then show all the signatures.
        if ( $this -> currentUser -> role == 'admin'){
            //get all the signatures
            $signatures =  Signature::all();
            foreach ( $signatures as $signature){
                $reviewDetails = $signature -> review ;
                $output = "";
                //Generate signatures for all 8 values
                for ( $i = 1 ; $i < 9 ; $i++ ){
                    $ch = curl_init();
                    $output  .= '<div style="margin-right:10px; display:inline">';
                    curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode($signature -> primaryText) .'&s=' .urlencode($signature -> secondaryText)  . '&t=' .urlencode($signature -> tertiaryText) . '&v=' . $i);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output .= curl_exec($ch);
                    $output .= "</div>";
                    curl_close($ch);
                }
                $signature -> preview = $output;
                if ( $reviewDetails -> reviewstatus == "0"){
                    $signature -> status = "Denied";
                    $signature -> comments = $reviewDetails -> comments;
                }
                else if ( $reviewDetails -> reviewstatus == "1"){
                    $signature -> status = "Approved";
                    $signature -> comments = $reviewDetails -> comments;
                }
                else{
                    $signature -> status = "Pending Review";
                    $signature -> comments = $reviewDetails -> comments;
                }
            }
            $data= array(
                'tabledata' => $signatures,
                'userRole' =>$this -> currentUser -> role

            );
            return view('signatures') -> with("pagedata" , $data);
        }
        else{
            //get signatures created by the current user.
            $signatures = ($this-> currentUser ->signatures);
            foreach ( $signatures as $signature){
                $reviewDetails = $signature -> review ;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode($signature -> primaryText) .'&s=' .urlencode($signature -> secondaryText)  . '&t=' .urlencode($signature -> tertiaryText) . '&v=' . $signature -> type);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                $signature -> preview = $output;
                if ( $reviewDetails -> reviewstatus == "0"){
                    $signature -> status = "Denied";
                    $signature -> comments = $reviewDetails -> comments;
                }
                else if ( $reviewDetails -> reviewstatus == "1"){
                    $signature -> status = "Approved";
                    $signature -> comments = $reviewDetails -> comments;
                }
                else{
                    $signature -> status = "Pending Review";
                    $signature -> comments = $reviewDetails -> comments;
                }
            }
            $data= array(
                'tabledata' => $signatures,
                'userRole' =>$this -> currentUser -> role
            );
            return view('signatures') -> with("pagedata" , $data);
        }

    }
    public function create(){
        //Show a list of all the signatures for the logged in user. If the user is
        //an admin show a list of all the signatures.
        $user = AppUser::find($_SERVER["HTTP_CAS_USER"]);
        if ( !empty($user)){
            //$username = $user -> username ;
            //Find all the signatures for this username.
            //$signatures = $user -> signatures() -> find('');
        }
        return view('createsignature');
    }
    public function save(){
        $signature = new Signature();
        $signature -> primaryText = Input::get("p");
        $signature -> secondaryText = Input::get("s");
        $signature -> tertiaryText  =Input::get("t");
        $signature -> type= Input::get('hdnsignaturetype');
        $signature -> signatureImgUrl = "";
        $signature -> userid = $this->currentUser -> username;
        $signature -> save();
        $review = new Review();
        $review -> reviewstatus = '-1'; //0 is pending 1 is approved, -1 is pending review.
        $review -> signatureid = $signature -> signatureid ;
        $review -> save();
        return Redirect::to('/signatures');
    }
    public function approve($id , $comment = null){
        if (Request::ajax()){
            //Check if the user is an admin
            if ( $this -> currentUser -> role == "admin"){
                //Check if the signature has been reviewed by anyone else ?
                $review = Signature::find($id) -> review;
                if (empty($review -> reviewedby)){
                    //Signature has not been reviewed yet. update the database table for this review
                    $review -> reviewedBy = $this->currentUser -> username ;
                    $review -> comments = $comment;
                    $review -> reviewstatus = 1 ; //Approved
                    $review -> save();
                    echo "0";
                }
                else{
                    //check if the same user has reviewed this signature earlier
                    //if yes , then approve it or send error response.
                        $review -> reviewedBy = $this->currentUser -> username ;
                        $review -> reviewstatus = 1 ; //Approved
                    $review -> comments = $comment;
                    $review -> save();
                        echo "0";
                }
                $data = $review -> comments ;
                $d = array("data" => $data);
                Mail::send('emails.approve', $d, function($message)
                {
                    $message->to('ssista@indiana.edu', 'Shanmukh Sista')
                        ->subject('Signature Approved!');
                });
            }
            //send an email to teh client.
        }
    }
    public function deny($id, $comment = null){
        if (Request::ajax()){
            //Check if the user is an admin
            if ( $this -> currentUser -> role == "admin"){
                //Check if the signature has been reviewed by anyone else ?
                $review = Signature::find($id) -> review;
                if (empty($review -> reviewedby)){
                    //Signature has not been reviewed yet. update the database table for this review
                    $review -> reviewedBy = $this->currentUser -> username ;
                    $review -> reviewstatus = 0 ; //deny
                    $review -> comments = $comment;
                    $review -> save();
                    echo "0";
                }
                else{
                    //check if the same user has reviewed this signature earlier
                    //if yes , then approve it or send error response.
                    $review -> reviewedBy = $this->currentUser -> username ;
                    $review -> reviewstatus = 0 ; //deny
                    $review -> comments = $comment;
                    $review -> save();
                    echo "0";
                }
                //send an email to the user.
                $data = $review -> comments ;
                $d = array("data" => $data);
                Mail::send('emails.denied', $d, function($message)
                {
                    $message->to('ssista@indiana.edu', 'Shanmukh Sista')
                        ->subject('Signature Request Denied!');
                });
            }
        }
    }

}
