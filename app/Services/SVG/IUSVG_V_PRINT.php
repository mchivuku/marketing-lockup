<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/24/15
 * Time: 4:06 PM
 */

namespace App\Services\SVG;

class IUSVG_V_PRINT extends IUSVG_V{

    public function __construct($p,$s,$t,$v){

        $this->tabColor='#a90533';
        parent::__construct($p,$s,$t,$v);

    }
}

