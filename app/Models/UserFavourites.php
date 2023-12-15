<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFavourites extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_favourite';

    protected $fillable = [
        'id',
        'user_id',
        'tournament_id',
        'team_id',
        'is_default',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function tournament()
    {
        return $this->belongsTo(\App\Models\Tournament::class, 'tournament_id');
    }
}
