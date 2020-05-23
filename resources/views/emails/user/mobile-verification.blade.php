@component('mail::message')
    ### We received a request to change your password

    Hi,
    This is your One Time Password.
    This otp will expire in 30 minutes.

    {{ $otp }}

    Thanks,
    {{ config('app.name') }}
@endcomponent
