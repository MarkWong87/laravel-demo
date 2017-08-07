<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcUser extends Model
{
    protected $table = 'ac_user';

    protected $primaryKey = 'user_id';

    protected $fillable = [''];

    protected $hidden = [''];

    public $timestamps = false;

}
