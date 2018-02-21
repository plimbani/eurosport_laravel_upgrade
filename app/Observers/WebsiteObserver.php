<?php

namespace Laraspace\Observers;

use Laraspace\Models\Website;
use Laraspace\Traits\TracksHistoryTrait;

class WebsiteObserver
{
    use TracksHistoryTrait;

    /**
     * Fields to observe
     *
     * @var string
     */
    protected $table = 'statistics';

    /**
     * Listen to the Website updating event.
     *
     * @param  \Laraspace\Models\Website  $website
     * @return void
     */
    public function saved(Website $website)
    {
        $this->track($website);
    }
}