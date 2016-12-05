<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param Logout $event
     *
     * @return void
     */
    public function handle(Logout $event)
    {
        /*
         * Check to see if the users account is confirmed and active
         */
        if ($event->user->is_verified === 1) {
            $event->user->last_active_time = Carbon::now();
            $event->user->is_online = 0;
            $event->user->is_active = 0;
            $event->user->save();
        }
    }
}
