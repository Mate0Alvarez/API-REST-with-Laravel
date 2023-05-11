<x-mail::message>
# Hello {{$user->name}}

Thank you for create a new account. Please, verify it clicking the following link:

<x-mail::button :url="route('verify', $user->verification_token)">
Confirm my account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
