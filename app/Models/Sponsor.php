<?php

namespace Laraspace\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sponsors';

    /**
     * Create sponsor logo url.
     *
     * @return string
     */
    public function sponsorLogo($key = null)
    {
        $path = config('filesystems.disks.s3.url').config('wot.imagePath.sponsor_logo');
        if ($key) {
            return $path.$key.'/'.$this->logo;
        }

        return $path.$this->logo;
    }
}
