@php
    $fabWhatsapp = \App\Models\Setting::get('whatsapp_number');
    $fabPhone = \App\Models\Setting::get('contact_phone');
@endphp

@if ($fabWhatsapp || $fabPhone)
    <div class="position-fixed d-flex flex-column gap-3" style="bottom: 24px; right: 24px; z-index: 1050;">
        @if ($fabPhone)
            <a
                href="tel:{{ $fabPhone }}"
                class="fab-btn fab-btn--call"
                aria-label="Call us"
            >
                <i class="bi bi-telephone-fill"></i>
            </a>
        @endif
        @if ($fabWhatsapp)
            <a
                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $fabWhatsapp) }}"
                target="_blank"
                rel="noopener"
                class="fab-btn fab-btn--whatsapp"
                aria-label="Chat on WhatsApp"
            >
                <i class="bi bi-whatsapp"></i>
            </a>
        @endif
    </div>
@endif
