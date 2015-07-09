<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppAdmin extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'username';
    protected $table = 'administrator';
    protected $dates = ['deleted_at'];

}
?>