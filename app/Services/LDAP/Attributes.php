<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 9:43 AM
 */
namespace App\Services;


class NetworkId{

    function __toString(){
        return "cn";
    }

}

class FirstName{

    function __toString(){
        return "givenname";
    }

}

class LastName{

    function __toString(){
        return "sn";
    }

}
