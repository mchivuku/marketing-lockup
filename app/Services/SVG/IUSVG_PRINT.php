<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/19/15
 * Time: 9:58 AM
 */

namespace App\Services\SVG;
class IUSVG_PRINT extends IUSVG {

    public function  addTab($h) {

        $r = $this->xml->addChild('rect');
        $r['width'] = $this->tabWidth;
        $r['height'] = $h;
        $r['fill'] = '#a90533';

    }
}

