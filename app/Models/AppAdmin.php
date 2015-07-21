<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppAdmin extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'administrator';
    protected $dates = array('deleted_at');

    /***
     * Function to return name
     * @param $username
     * @return \StdClass
     */

    public function getName(){
           return  sprintf("%s,%s", $this->ldapService->getLastName($this->username),
                $this->ldapService->getFirstName($this->username));
    }

}
?>