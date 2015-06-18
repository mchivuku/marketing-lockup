<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 1:29 PM
 */


namespace App\Http\Controllers;

use App\Models\Signature;

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
                $reviewDetails = $signature -> review;
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

}