<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/27/15
 * Time: 11:43 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model {
    protected $primaryKey = 'signatureid';
    protected $table = 'signature';

    public $fillable = array('username', 'primaryText', 'secondaryText','tertiaryText','named');

    public function signaturereviews(){
       return $this->hasMany('App\Models\SignatureReview','signatureid','signatureid');
    }

    public function reviewstatus(){
        return $this->hasOne('App\Models\ReviewStatus','id','statusId');
    }




    //TODO - fix - named school
    public function getSignaturePreview(){

        $output="";

        if($this->named=='1'){
            for ( $i = 1 ; $i < 9 ; $i++ ){
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode
                    ($this-> primaryText) .'&s=' .urlencode($this -> secondaryText)  . '&t=' .
                    urlencode($this ->tertiaryText) . '&v=' . $i);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output .= curl_exec($ch);

                curl_close($ch);
            }

        }else{
            for ( $i = 1 ; $i < 9 ; $i++ ){
                $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode
                    ($this-> primaryText) .'&s=' .urlencode($this -> secondaryText)  . '&t=' .
                    urlencode($this ->tertiaryText) . '&v=' . $i);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output .= curl_exec($ch);
                 curl_close($ch);
            }

        }


        return $output;
    }

    public function getSignatureThumbnail(){

        $output="";


        $ch = curl_init();
        $output  .= '<div style="margin-right:10px; display:inline">';
        curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode
            ($this-> primaryText) .'&s=' .urlencode($this -> secondaryText)  . '&t=' .
            urlencode($this ->tertiaryText) . '&v=' . 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output .= curl_exec($ch);
        $output .= "</div>";
        curl_close($ch);


        return $output;
    }

}