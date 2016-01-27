<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 1/26/16
 * Time: 8:11 PM
 */

namespace App\Services\SVG;
require_once 'SVGBase.php';

class IUSVGBase extends SVGBase {

    protected $tabHeight = 68;
    protected $tabWidth = 58;


    protected $primary_leading_x =  0.41;
    protected $secondary_leading_x = 0.35;
    protected $tertiary_leading_x = 0.3;

    protected $primary,$secondary,$tertiary,$subprimary, $subsecondary;

    function __construct($p,$s,$t,$split_p,$split_s) {

        // initialize primary, subprimary
        if($split_p){
            $result = $this->splitText(strtoupper($p));

            $this->primary = $result[0];
            $this->subprimary = $result[1];

        }else{

            $this->primary=strtoupper($p);
        }

        // split secondary
        if($split_s){
            // initialize secondary, subsecondary
            $result = $this->splitText(strtoupper($s));

            $this->secondary = $result[0];
            $this->subsecondary = $result[1];

        }else{

            $this->secondary=strtoupper($s);
        }

        $this->tertiary=$t;

        parent::__construct();

    }

    function init($width, $height, $tab_height='',$tab_x=0,$tab_y=0,$fixed=false){
        parent::init($width,$height);

        if($tab_height!="")
            $this->addTab($tab_height,$tab_x,$tab_y);
        else
            $this->addTab($height,$tab_x,$tab_y);

        $this->addLogo($tab_x,$tab_y);
    }

    function addTab($h,$x=0,$y=0) {


        $r = $this->xml->addChild('rect');
        $r['width'] = $this->tabWidth;
        $r['height'] = $h;
        $r['fill'] = '#951B1E';
        $r['x']="$x";
        $r['y']="$y";


    }

    function addLogo($x=0,$y=0) {

        $translate = "translate($x,$y)";

        $logo = '<polygon  fill="#FFFFFF" points="35.6,16.4 35.6,20 38.1,20 38.1,40.1 32.8,40.1
         32.8,13.6 35.6,13.6 35.6,10 22.4,10 22.4,13.6
	25.2,13.6 25.2,40.1 19.9,40.1 19.9,20 22.4,20 22.4,16.4 10,16.4 10,20 12.8,20 12.8,43.3 17.4,47.9 25.2,47.9 25.2,53.3
	22.4,53.3 22.4,58 35.6,58 35.6,53.3 32.8,53.3 32.8,47.9 40.6,47.9 45.2,43.3 45.2,20 48,20 48,16.4"
	transform="'.$translate.'"/>';
        $this->addXMLStr($this->xml, $logo);
    }

    /* Function to break primary into two words - Waiting for Approval
    protected function getprimary(){

        $text1="";
        $text2="";
        if(strlen($this->primary)>24){

           $words = preg_split('/\s+/', $this->primary);
           $length = 0;
           $last_part = 0;
            for (; $last_part < count($words); ++$last_part) {
                $length += strlen($words[$last_part]);
                if ($length > 24) { break; }
            }

            $text1 = strtoupper(implode(" ",array_slice($words, 0, $last_part+1)));
            $text2=  strtoupper(implode(" ", array_slice($words, $last_part+1,count($words))));

        }
        else{
            $text1 = strtoupper($this->primary);
            $text2="";
        }

        return array('p'=>$text1,'subp'=>$text2);
    }*/

    private function splitText($text){

        if(strlen($text)>24){
            //get words
            $string=  wordwrap($text, 24, "@");
            if(strpos($string,'@')!==false){
                $strings = explode("@",$string);
                $text1=strtoupper($strings[0]);

                if(count($strings)>1){
                    $name = array_shift($strings);
                    $text2 = strtoupper(implode(' ', $strings));
                }

            }else{

                $text1 = substr($text,0,24);
                $text2=substr($text,24,strlen($text));

            }
        }
        else{
            $text1 = strtoupper($text);
            $text2="";
        }

        return array($text1,$text2);

    }

    protected function getprimary(){
        $result = $this->splitText($this->primary);
        $this->primary = $result[0];
        $this->subPrimary = $result[1];
    }


    protected function getsecondary(){
        $result= $this->splitText($this->primary);
        $this->secondary = $result[0];
        $this->subsecondary = $result[1];
    }


}