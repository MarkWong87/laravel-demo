<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JcContent extends Model
{
    protected $table = 'jc_content';

    protected $primaryKey = 'content_id';

    protected $fillable = [''];

    protected $hidden = [''];

    public $timestamps = false;

}
