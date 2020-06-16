<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $otp
     */
    public function __construct(User $user, $otp)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.address'))
            ->subject(config('app.name') . ' Password Reset OTP')
            ->markdown('emails.user.mobile-verification')
            ->with([
                'userFullName' => $this->user->fullName,
                'otp' => $this->otp
            ]);
    }
}
