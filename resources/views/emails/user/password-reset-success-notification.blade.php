@component('mail::message')
    # Congratulations

    Hi {{ $name }},
    You have successfully set your new password!

    Thanks,
    {{ config('app.name') }}
@endcomponent
