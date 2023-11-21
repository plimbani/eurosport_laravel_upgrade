<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'itineraries';

    /**
     * Get all of the items of an itinerary.
     */
    public function items()
    {
        return $this->hasMany(\Laraspace\Models\ItineraryItem::class);
    }
}
