<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/22/15
 * Time: 1:51 PM
 */

namespace App\Models\ViewModels;


class BaseViewModel{

    public static function  renderAttribute($key,$value){
            if (is_numeric($key)) $key = $value;
            if ( ! is_null($value)) return $key.'="'.e($value).'"';

    }


}