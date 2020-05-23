<?php

namespace App\Providers;

use App\Events\UserHasLoggedIn;
use App\Events\UserHasRequestedVerification;
use App\Events\UserResetPasswordHasSucceeded;
use App\Listeners\RecordLoginHistory;
use App\Listeners\SendMobileValidation;
use App\Listeners\SendPasswordResetSuccessNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserHasLoggedIn::class => [
            RecordLoginHistory::class
        ],
        UserHasRequestedVerification::class => [
            SendMobileValidation::class
        ],
        UserResetPasswordHasSucceeded::class => [
            SendPasswordResetSuccessNotification::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
