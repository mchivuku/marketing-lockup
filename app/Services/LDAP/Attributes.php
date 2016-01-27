<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/15/15
 * Time: 9:43 AM
 */
namespace App\Services;

class NetworkId
{
    public function __toString()
    {
        return "cn";
    }
}

class FirstName
{
    public function __toString()
    { 
       

        return "givenname";
    }
}

class LastName
{
    public function __toString()
    {
        return "sn";
    }
}


class Email
{
    public function __toString()
    {
        return "mail";
    }
}
