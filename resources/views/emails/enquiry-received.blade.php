<x-mail::message>
# New {{ $enquiry->type === 'quote' ? 'Quote Request' : 'Enquiry' }}

@if ($enquiry->product)
**Product:** {{ $enquiry->product->name }} ({{ $enquiry->product->sku }})
@endif

**Name:** {{ $enquiry->name }}
**Email:** {{ $enquiry->email }}
@if ($enquiry->phone)
**Phone:** {{ $enquiry->phone }}
@endif
@if ($enquiry->company)
**Company:** {{ $enquiry->company }}
@endif

@if ($enquiry->message)
**Message:**

{{ $enquiry->message }}
@endif

<x-mail::button :url="route('admin.enquiries.show', $enquiry)">
View in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
