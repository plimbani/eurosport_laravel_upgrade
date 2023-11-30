<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryAgeCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history_age_categories';

    /**
     * Get all of the teams of the age category.
     */
    public function teams()
    {
        return $this->hasMany(\App\Models\HistoryTeam::class, 'history_age_category_id', 'id');
    }
}
