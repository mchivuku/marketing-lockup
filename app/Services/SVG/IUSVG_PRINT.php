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

        $this->tabColor='#a90533';
        parent::__construct($p,$s,$t,$v);

    }
}

