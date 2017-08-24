<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Club extends Model
{
   public function tournament()
    {
        return $this->belongsToMany('Laraspace\Models\Tournament', 'tournament_club', 'club_id','tournament_id');
    }

	use SoftDeletes;

  protected $table = 'clubs';

   protected $fillable = [
       'user_id','name',
    ];
}
