<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    protected $table = 'cars';

    protected $primaryKey = 'id';

    protected $fillable = ['*'];

    protected $hidden = [''];

    public $timestamps = false;

}
