<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JcContentCount extends Model
{
    protected $table = 'jc_content_count';

    protected $primaryKey = 'content_id';

    protected $fillable = [''];

    protected $hidden = [''];

    public $timestamps = false;

}
