@component('mail::message')
    # Congratulations

    Hi {{ $userFullName }},
    You have successfully set your new password!

    Thanks,
    {{ config('app.name') }}
@endcomponent
