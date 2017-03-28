<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
	protected $table = 'competitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'tournament_id','tournament_competation_template_id','name','team_size','competation_type','created_at','updated_at','deleted_at']; 
}
