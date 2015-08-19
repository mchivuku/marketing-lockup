<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/27/15
 * Time: 11:43 AM
 */

namespace App\Models;
use App\Services\SVG\IUSVG;
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
    public function getAllSchoolTags(){return array(1,2,3,4,5,6,7);}


    /** Get Preview for signatures  */
    public function getSignaturePreview(){

        $output="";
        $previews = function($tags){
            $output="";
            foreach($tags as $tag){
                $output.= "<div id='svg-preview'>".new IUSVG($this->primaryText,$this->secondaryText,
                        $this->tertiaryText,
                    $tag)."</div>";
            }

            return $output;
        };

        if($this->named=='1'){
            $output = $previews($this->getNamedSchoolTags());

        }else{
            $output = $previews($this->getAllSchoolTags());

        }


        return $output;
    }

    public function getSignatureThumbnail(){
        return new IUSVG($this->primaryText,$this->secondaryText,$this->tertiaryText,1);
    }

    public function build(){


        $svg_convert = new SVGConvert($this->primaryText,$this->secondaryText,$this->tertiaryText,
            ($this->named==0)?$this->getAllSchoolTags():$this->getNamedSchoolTags());

        $return = $svg_convert->build();
        return $return;


    }

}