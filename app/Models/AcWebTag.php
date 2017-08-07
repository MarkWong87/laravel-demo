<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcWebTag extends Model
{
    protected $table = 'ac_web_tag';

    protected $primaryKey = 'id';

    protected $fillable = ['*'];

    protected $hidden = [''];

    public $timestamps = false;

}
