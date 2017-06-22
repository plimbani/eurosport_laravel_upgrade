<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Club extends Model
{
	use SoftDeletes;

  protected $table = 'clubs';

   protected $fillable = [
       'user_id','name',
    ];
}
