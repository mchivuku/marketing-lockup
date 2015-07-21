<?php
/**
 * Created by PhpStorm.
 * User: sissta
 * Date: 3/30/15
 * Time: 9:35 AM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SignatureReview  extends Model{
    protected $table = "signatureReview";
    protected $primaryKey = 'id';

    protected $fillable = array('signatureid', 'reviewedBy', 'reviewStatus','comments','emailSent','created_at',
        'isActive','updated_at');


    public function signatures(){
        return $this->hasMany('Signature');
    }

    public function status(){
        return $this->hasOne('ReviewStatus','reviewstatus','id');

    }

}