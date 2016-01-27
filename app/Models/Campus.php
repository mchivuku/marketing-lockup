<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 1/26/16
 * Time: 8:13 PM
 */

namespace App\Models;

class Campus{


    public static function getAllCampuses(){
        return   ['IU'=>'IU','IU Bloomington'=>'IU Bloomington', 'IUPUI'=>'IUPUI','IU East'=>'IU East','IU Kokomo'=>'IU Kokomo',
            'IU Northwest'=>'IU Northwest','IU South Bend'=>'IU South Bend','IU Southeast'=>'IU Southeast',"IUPUC"=>"IUPUC"];

    }

    public static function getIUPUILikeCampuses(){

        return  ["IUPUI"=>"IUPUI","IUPUC"=>"IUPUC"];
    }



}