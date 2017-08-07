<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class JcHotWord extends Model
{
    protected $table = 'jc_hotword';

    protected $primaryKey = 'hotword_id';

    protected $fillable = ['*'];

    protected $hidden = [''];

    public $timestamps = false;

}
