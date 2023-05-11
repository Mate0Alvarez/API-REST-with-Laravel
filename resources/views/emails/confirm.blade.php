<x-mail::message>
# Hello {{$user->name}}

You have changed your email. Please, confirm by clicking the following link:

<x-mail::button :url="route('verify', $user->verification_token)">
Confirm my account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
