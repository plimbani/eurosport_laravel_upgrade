<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryYears extends Model
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
        return $this->hasMany('Laraspace\Models\HistoryAgeCategories', 'history_year_id', 'id');
    }
}
