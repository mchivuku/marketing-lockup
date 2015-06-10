<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/30/15
 * Time: 9:35 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review  extends Model{
    protected $table = "signaturereview";
    protected $primaryKey = 'id';
    public function signature(){
        return $this -> belongsTo('\App\Models\Signature', 'signatureid' );
    }
    public function reviewer(){
        return $this -> belongsTo("\App\Models\AppUser", 'reviewedby');
    }
}