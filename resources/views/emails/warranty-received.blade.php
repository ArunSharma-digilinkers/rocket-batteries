<x-mail::message>
# New Warranty Registration

**Product:** {{ $warranty->product->name }} ({{ $warranty->product->sku }})
**Serial No:** {{ $warranty->serial_no }}
**Customer:** {{ $warranty->customer_name }}
**Mobile:** {{ $warranty->mobile }}
@if ($warranty->email)
**Email:** {{ $warranty->email }}
@endif
**Purchase Date:** {{ $warranty->purchase_date->format('d M Y') }}
@if ($warranty->dealer_name)
**Dealer:** {{ $warranty->dealer_name }}
@endif

<x-mail::button :url="route('admin.warranties.show', $warranty)">
Review in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
