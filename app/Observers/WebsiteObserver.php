<?php

namespace Laraspace\Observers;

use Laraspace\Models\Website;
use Laraspace\Traits\TracksHistoryTrait;

class WebsiteObserver
{
    use TracksHistoryTrait;

    /**
     * Listen to the Website created event.
     *
     * @param  \Laraspace\Models\Website  $website
     * @return void
     */
    public function created(Website $website)
    {
        \Log::info('created');
    }

    /**
     * Listen to the Website updated event.
     *
     * @param  \Laraspace\Models\Website  $website
     * @return void
     */
    public function updated(Website $website)
    {
        \Log::info('updated');
    }

    /**
     * Listen to the Website deleted event.
     *
     * @param  \Laraspace\Models\Website  $website
     * @return void
     */
    public function deleted(Website $website)
    {
        \Log::info('deleted');
    }
}