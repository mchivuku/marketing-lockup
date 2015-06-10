<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $primaryKey = 'username';
    protected $table = 'users';
    // a user can have many signatures
    public function signatures(){
        return $this -> hasMany("App\Models\Signature", 'userid');
    }
    public function reviews(){
        return $this -> hasMany("\App\Models\Review", 'reviewedby');
    }
}
?>