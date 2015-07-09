<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/19/15
 * Time: 10:01 AM
 */

class SignatureTypes  extends Model{
    protected $table = "signatureType";
    protected $primaryKey = 'id';
    public function signature(){
        return $this -> hasMany('\App\Models\Signature', 'signatureid' );
    }

}