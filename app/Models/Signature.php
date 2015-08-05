<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/27/15
 * Time: 11:43 AM
 */

namespace App\Models;
use App\Services\SVGConversion\SVGConvert;
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



    /** Helper Functions  */

    public function getNamedSchoolTags(){return array(1,2,3,4);}
    public function getAllSchoolTags(){return array(1,2,3,4,5,6);}



    /** Get Preview for signatures  */
    public function getSignaturePreview(){

        $output="";

        if($this->named=='1'){
            foreach($this->getNamedSchoolTags() as $tag){
                
            }

        }else{
            foreach($this->getAllSchoolTags() as $tag){
                $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode
                    ($this-> primaryText) .'&s=' .urlencode($this -> secondaryText)  . '&t=' .
                    urlencode($this ->tertiaryText) . '&v=' . $tag);
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

    public function build(){

        $svg_convert = new SVGConvert($this->primaryText,$this->secondaryText,$this->tertiaryText,
            ($this->named==0)?$this->getAllSchoolTags():$this->getNamedSchoolTags());

        $return = $svg_convert->build();
        return $return;


    }

}