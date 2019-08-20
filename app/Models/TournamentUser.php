<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentUser extends Model
{
	protected $table = 'tournament_user';

	public $timestamps = false;

	public function tournaments()
    {
        return $this->hasOne('Laraspace\Models\Tournament','id','tournament_id');
    }

    public function filter_tournaments_with_endate()
    {
        $prevDay = date('Y-m-d',strtotime("-1 days"));
        return $this->hasOne('Laraspace\Models\Tournament','id','tournament_id')->whereDate('end_date',$prevDay);
    }
}
