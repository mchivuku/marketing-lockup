<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/27/15
 * Time: 11:43 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model {
    protected $primaryKey = 'signatureid';
    protected $table = 'usersignatures';
    public function user(){
        return $this -> belongsTo('\App\Models\AppUser' , 'userid');
    }
    public function review(){
        return $this -> hasOne('\App\Models\Review', 'signatureid');
    }
}