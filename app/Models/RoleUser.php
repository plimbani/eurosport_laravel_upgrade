<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $table = 'role_user';

	public function tournament_user()
    {
        return $this->hasMany('Laraspace\Models\TournamentUser', 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->hasOne('Laraspace\Models\User','id','user_id');
    }
}
