<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserHasLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $device_name;
    public $ip_address;

    /**
     * Create a new event instance.
     * @param $user
     * @param $device_name
     * @param $ip_address
     */
    public function __construct($user, $device_name, $ip_address)
    {
        $this->user = $user;
        $this->device_name = $device_name;
        $this->ip_address = $ip_address;
    }
}
