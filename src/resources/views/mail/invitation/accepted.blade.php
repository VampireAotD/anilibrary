<x-mail::message>
# You've been invited to {{ config('app.name') }}!

<x-mail::panel>
To proceed and register, please accept the invitation by pressing button below.
Invitation is available only for {{ config('auth.registration.expire') }} minutes!
</x-mail::panel>

<x-mail::button :url="$url">
Accept invitation
</x-mail::button>

<x-slot:subcopy>
If you’re having trouble clicking the button, copy and paste the URL below into your web browser:
[{{ $url }}]({{ $url }})
</x-slot:subcopy>
</x-mail::message>
