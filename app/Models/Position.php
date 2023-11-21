<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positions';

    /**
     * Get Team Details.
     */
    public function team()
    {
        return $this->belongsTo('Laraspace\Models\Team', 'team_id');
    }
}
