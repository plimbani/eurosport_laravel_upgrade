<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
	use SoftDeletes;

  protected $table = 'settings';

  protected $fillable = [
       'id', 'user_id','option','value'
  ];
   /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get User Details.
     */
    public function user()
    {
        return $this->belongsTo('Laraspace\Models\User', 'user_id');
    }
}
