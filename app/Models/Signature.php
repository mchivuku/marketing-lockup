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

    public $fillable = array('username', 'primaryText', 'secondaryText','tertiaryText','named',
        'fullName');

    public function signaturereviews(){
       return $this->hasMany('App\Models\SignatureReview','signatureid','signatureid');
    }

    public function reviewstatus(){
        return $this->hasOne('App\Models\ReviewStatus','id','statusId');
    }


    private $classname  = array('h'=>'App\Services\SVG\IUSVG','v'=>'App\Services\SVG\IUSVG_V');

    /** Helper Functions  */
    public function getNamedSchoolTags(){return array('h'=>array(2,4,6,9),'v'=>array(1,2,3));}
    public function getAllSchoolTags(){return array('h'=> array(1,3,5,7,8),'v'=>array(1,4,5));}


    private function getHorizontalAllSchoolTags(){
        $tags = $this->getAllSchoolTags();
        return $tags['h'];
    }

    private function getVerticalAllSchoolTags(){
        $tags = $this->getAllSchoolTags();
        return $tags['v'];
    }

    private function getHorizontalNamedSchoolTags(){
        $tags = $this->getNamedSchoolTags();
        return $tags['h'];
    }


    private function getVerticalNamedSchoolTags(){
        $tags = $this->getNamedSchoolTags();
        return $tags['v'];
    }

    /** Get Preview for signatures  */
    public function getSignaturePreview(){

        $output="";
        $previews = function($tags,$classname){
            $output="";
            foreach($tags as $tag){

                $svg = new $classname($this->primaryText,$this->secondaryText,
                    $this->tertiaryText,$tag);

                if($svg!="")
                 $output.= "<div id='svg-preview'>".$svg."</div>";
            }


            return $output;
        };

        if($this->named=='1'){
            // include = vertical and horizontal
            $alltags = $this->getNamedSchoolTags();

                $output = $previews($alltags['h'],'App\Services\SVG\IUSVG');
                $output .= $previews($alltags['v'],'App\Services\SVG\IUSVG_V');

        }else{
            $alltags =$this->getAllSchoolTags();

                $output = $previews($alltags['h'],'App\Services\SVG\IUSVG');
                $output .= $previews($alltags['v'],'App\Services\SVG\IUSVG_V');


        }


        return $output===""?"No lock-ups to preview":$output;
    }

    public function getSignatureThumbnail()
    {
        if ($this->named == 1) {
            if ($this->tertiaryText != '')
                return new IUSVG($this->primaryText, $this->secondaryText, $this->tertiaryText, 6);
            else
                return new IUSVG($this->primaryText, $this->secondaryText, $this->tertiaryText, 4);
        } else
        {
            if($this->tertiaryText!='')
                return new IUSVG($this->primaryText,$this->secondaryText,$this->tertiaryText,7);
            else
                return new IUSVG($this->primaryText,$this->secondaryText,$this->tertiaryText,5);
        }


    }

    public function build(){

        $svg_convert = new SVGConvert(
            $this->primaryText,
            $this->secondaryText,
            $this->tertiaryText,
            ($this->named==0)?$this->getHorizontalAllSchoolTags():$this->getHorizontalNamedSchoolTags(),
            ($this->named==0)?$this->getVerticalAllSchoolTags():$this->getVerticalNamedSchoolTags()
            );

        $return = $svg_convert->build();
        return $return;


    }

}