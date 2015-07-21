<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/19/15
 * Time: 9:51 AM
 */


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReviewStatus  extends Model{
    protected $table = "reviewStatus";
    protected $primaryKey = 'id';

    public function signatures(){
        return $this->hasMany('Signatures');
    }


}