<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Organiser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organisers';

    /**
     * Create organiser logo url.
     *
     * @return string
     */
    public function organiserLogo($key = null)
    {
        $path = config('filesystems.disks.s3.url').config('wot.imagePath.organiser_logo');
        if ($key) {
            return $path.$key.'/'.$this->logo;
        }

        return $path.$this->logo;
    }
}
