<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserHasRequestedVerification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $code;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $code
     */
    public function __construct(User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }
}
