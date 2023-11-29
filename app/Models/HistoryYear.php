<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryYear extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history_years';

    /**
     * Get all of the teams of the age category.
     */
    public function age_categories()
    {
        return $this->hasMany(\App\Models\HistoryAgeCategory::class, 'history_year_id', 'id');
    }
}
