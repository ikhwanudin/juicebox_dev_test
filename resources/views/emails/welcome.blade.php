<x-mail::message>
# Welcome to Our Platform, {{ $user->name }}!
<x-mail::button :url="'#'">
Explore
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
