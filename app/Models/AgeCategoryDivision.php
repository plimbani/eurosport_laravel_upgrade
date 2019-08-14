<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class AgeCategoryDivision extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'age_category_divisions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
