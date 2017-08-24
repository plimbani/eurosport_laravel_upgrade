<?php
namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $table = 'venues';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'address1', 'address2', 'address3',
     'county', 'state', 'country', 'city', 'postcode', 'contact_no', 'email_address','tournament_id','organiser' ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
