<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class AgeCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'age_categories';

    /**
     * Get all of the teams of the age category.
     */
    public function teams()
    {
        return $this->hasMany(\Laraspace\Models\AgeCategoryTeam::class);
    }
}
