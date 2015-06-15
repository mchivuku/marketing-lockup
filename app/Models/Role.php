<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/11/15
 * Time: 3:44 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    protected $primaryKey = 'id';
    protected $table = 'role';

    public function users()
    {
        return $this->hasMany('AppUser');
    }


}