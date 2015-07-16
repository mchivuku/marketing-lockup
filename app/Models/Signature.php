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
    protected $table = 'signature';

    public $fillable = array('username', 'primaryText', 'secondaryText','tertiaryText','type');

    public function signaturereviews(){
        $this->hasMany('SignatureReview');
    }

    public function signatureType(){
        $this->hasOne('SignatureType','id','type');
    }

    public function getSignaturePreview(){

        $ch = curl_init();
        $output  = '<div style="margin-right:10px; display:inline">';
        curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' .
            urlencode($this -> primaryText) .'&s=' .urlencode($this -> secondaryText)  . '&t=' .urlencode
            ($this -> tertiaryText) . '&v=' . $this->type);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output .= curl_exec($ch);

        $output .= "</div>";

        curl_close($ch);

        return $output;
    }

}