@component('mail::message')
# The contact form was submitted on behalf of {{ $name }}

    - Topic: {{ $topic }}
    - Name: {{ $name }}
    - Email Address: {{ $email }}
    - Message: {{ $message }}

Thanks,<br>
{{ config('app.name') }}

@endcomponent
