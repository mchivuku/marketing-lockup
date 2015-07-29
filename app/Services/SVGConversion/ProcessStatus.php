<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/29/15
 * Time: 1:22 PM
 */

namespace App\Services\SVGConversion;
class ProcessStatus{

    public $status;
    public $message;

    public function __construct($status,$message=""){
        $this->status=$status;
        $this->message=$message;
    }
}