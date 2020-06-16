<?php

namespace App\Listeners;

use App\Events\UserHasRequestedVerification;
use App\Mail\PasswordResetVerification;
use Illuminate\Support\Facades\Mail;

class SendMobileValidation
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
     * @param  UserHasRequestedVerification  $event
     * @return void
     */
    public function handle(UserHasRequestedVerification $event)
    {
        return Mail::to($event->user->email)
            ->send(new PasswordResetVerification($event->user, $event->code));
    }
}
