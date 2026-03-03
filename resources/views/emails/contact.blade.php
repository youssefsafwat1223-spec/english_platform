<x-mail::message>
# New Contact Form Message

You have received a new message from the platform's contact form.

**From:** {{ $data['name'] }} ({{ $data['email'] }})
**Subject:** {{ $data['subject'] }}

**Message:**
<div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px; margin-bottom: 20px;">
{{ $data['message'] }}
</div>

<x-mail::button :url="config('app.url') . '/admin'">
Go to Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
