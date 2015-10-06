<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/5/15
 * Time: 11:08 AM
 */
namespace App\Services\SVG;
class IUSVGBase extends SVGBase {

    protected $tabHeight = 68;
    protected $tabWidth = 58;
    protected $tabColor;

    protected $primary_leading_x =  0.41;

    protected $secondary_leading_x = 0.35;
    protected $tertiary_leading_x = 0.3;



    function __construct() {
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

    function addTab($h,$x=0,$y=0){

        $r = $this->xml->addChild('rect');
        $r['width'] = $this->tabWidth;
        $r['height'] = $h;
        $r['fill'] = '#951B1E';;
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

    function required($parameters=array()){

        $is_null_empty = function($str){
            return isset($str)&& $str!="";
        };

        $filtered_items = array_filter($parameters, function($item)use(&$is_null_empty){
            return !$is_null_empty($item);
        });


        return !count($filtered_items)>0;
    }

}