<?php

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

new class extends Component
{
    public int $productId;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $company = '';
    public string $message = '';

    // Honeypot: real users never fill this in; bots typically do.
    public string $website = '';

    public bool $submitted = false;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->website !== '') {
            $this->submitted = true;

            return;
        }

        $throttleKey = 'enquiry:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('name', 'Too many enquiries submitted. Please try again later.');

            return;
        }

        RateLimiter::hit($throttleKey, 600);

        $enquiry = Enquiry::create([
            'product_id' => $this->productId,
            'type' => 'quote',
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'message' => $this->message,
            'status' => 'new',
            'source' => 'product_detail',
            'ip_address' => request()->ip(),
        ]);

        if ($adminEmail = Setting::get('contact_email')) {
            Mail::to($adminEmail)->send(new EnquiryReceived($enquiry->load('product')));
        }

        $this->reset(['name', 'email', 'phone', 'company', 'message']);
        $this->submitted = true;
    }
};
?>

<div>
    @if ($submitted)
        <div class="alert alert-success">Thank you — your enquiry has been received. We'll get back to you shortly.</div>
    @else
        <form wire:submit="submit">
            <label for="quote-website-{{ $productId }}" class="visually-hidden">Leave this field blank</label>
            <input type="text" id="quote-website-{{ $productId }}" wire:model="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="mb-3">
                <label for="quote-name-{{ $productId }}" class="form-label">Name</label>
                <input type="text" id="quote-name-{{ $productId }}" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="quote-email-{{ $productId }}" class="form-label">Email</label>
                <input type="email" id="quote-email-{{ $productId }}" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="quote-phone-{{ $productId }}" class="form-label">Phone</label>
                <input type="text" id="quote-phone-{{ $productId }}" class="form-control @error('phone') is-invalid @enderror" wire:model="phone">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="quote-company-{{ $productId }}" class="form-label">Company</label>
                <input type="text" id="quote-company-{{ $productId }}" class="form-control @error('company') is-invalid @enderror" wire:model="company">
                @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="quote-message-{{ $productId }}" class="form-label">Message</label>
                <textarea id="quote-message-{{ $productId }}" class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="4"></textarea>
                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                <span wire:loading.remove>Request Quote</span>
                <span wire:loading>Sending…</span>
            </button>
        </form>
    @endif
</div>
