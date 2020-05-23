<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserHasRequestedVerification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email;
    public $code;

    /**
     * Create a new event instance.
     *
     * @param $email
     * @param $code
     */
    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }
}
