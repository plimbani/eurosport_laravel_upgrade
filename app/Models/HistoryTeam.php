<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryTeam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history_teams';

    /**
     * Get country of the team.
     */
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }
}
