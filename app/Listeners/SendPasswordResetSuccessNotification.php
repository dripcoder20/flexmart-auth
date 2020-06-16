<?php

namespace App\Listeners;

use App\Events\UserResetPasswordHasSucceeded;
use App\Mail\PasswordResetSuccessNotification;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetSuccessNotification
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
     * @param  UserResetPasswordHasSucceeded  $event
     * @return void
     */
    public function handle(UserResetPasswordHasSucceeded $event)
    {
        return Mail::to($event->user->email)
            ->send(new PasswordResetSuccessNotification($event->user));
    }
}
