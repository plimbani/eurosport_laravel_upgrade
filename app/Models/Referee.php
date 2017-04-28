<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends Model
{
    use SoftDeletes;

    protected $table = 'referee';

    protected $fillable = [
        'user_id', 'availability', 'tournament_id','first_name','last_name','telephone','email','comments', 'age_group_id'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function matchFixture()
    {
        return $this->hasMany('Laraspace\Models\TempFixture');
    }
}
