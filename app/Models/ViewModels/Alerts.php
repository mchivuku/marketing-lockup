<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/22/15
 * Time: 10:15 AM
 */

namespace App\Models\ViewModels;

class Alerts
{
    const SUCCESS = "success";
    const WARNING = "warning";
    const ALERT = "alert";
    const INFORMATION = "info";
    const SECONDARY = "secondary";

    public static function all(){ return array(self::SUCCESS,self::INFORMATION,self::SECONDARY,self::WARNING,

        self::ALERT
        );}
}