<?php

namespace App\Listeners;

use App\Events\UserHasLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordLoginHistory implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserHasLoggedIn  $event
     * @return void
     */
    public function handle(UserHasLoggedIn $event)
    {
        $event->user->logins()->create([
            'user_id' => $event->user->id,
            'ip_address' => $event->ip_address,
            'device_name' => $event->device_name['device_name']
        ]);
    }
}
