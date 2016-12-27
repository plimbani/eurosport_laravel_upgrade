<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgeGroup extends Model
{
    use SoftDeletes;


  
    protected $table = 'age_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the user that belongs to the tournament.
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
