<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    use AuthenticatesUsers;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        /*
         * Check to see if the users account is confirmed and active
         */
        if ($event->user->is_verified === 1) {
            $event->user->last_login_time = Carbon::now();
            $event->user->last_active_time = Carbon::now();
            $event->user->is_online = 1;
            $event->user->is_active = 1;
            $event->user->save();
        }
    }
}
