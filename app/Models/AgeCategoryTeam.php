<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class AgeCategoryTeam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'age_category_teams';

    /**
     * Get country of the team.
     */
    public function country()
    {
        return $this->belongsTo(\Laraspace\Models\Country::class);
    }
}
