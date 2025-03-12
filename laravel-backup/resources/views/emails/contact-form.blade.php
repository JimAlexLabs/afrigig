@component('mail::message')
# New Contact Form Submission

You have received a new contact form submission from {{ $data['name'] }}.

**Email:** {{ $data['email'] }}

**Message:**
{{ $data['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent 