<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $table = 'user_login_history';
    protected $fillable = [
        'user_id',
        'ip_address',
        'device_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
