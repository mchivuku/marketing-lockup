<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/24/15
 * Time: 2:22 PM
 */

class OperationStatus{

    public $status;
    public $message;

    public function __construct($status,$message=""){
        $this->status=$status;
        $this->message=$message;
    }
}