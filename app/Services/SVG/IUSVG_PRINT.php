<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/19/15
 * Time: 9:58 AM
 */

namespace App\Services\SVG;
class IUSVG_PRINT extends IUSVG {

    public function __construct($p,$s,$t,$v){


        parent::__construct($p,$s,$t,$v);


    }

    function addTab($h,$x=0,$y=0){

        $r = $this->xml->addChild('rect');
        $r['width'] = $this->tabWidth;
        $r['height'] = $h;
        $r['fill'] = '#a90533';;
        $r['x']="$x";
        $r['y']="$y";


    }
}

