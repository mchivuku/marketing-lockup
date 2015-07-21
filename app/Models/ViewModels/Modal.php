<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/11/15
 * Time: 1:34 PM
 */

namespace App\Models\ViewModels;
class Modal{

    public $title;
    public $content;
    private $attributes=array();

    public function setAttribute($k,$v){
        $this->attributes[$k]=$v;
    }

    public function getAttributes(){
        return $this->attributes;
    }

}