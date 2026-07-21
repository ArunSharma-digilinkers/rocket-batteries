<?php

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
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
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
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

        $message = $this->subject !== ''
            ? "Subject: {$this->subject}\n\n{$this->message}"
            : $this->message;

        $enquiry = Enquiry::create([
            'product_id' => null,
            'type' => 'general',
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $message,
            'status' => 'new',
            'source' => 'contact_page',
            'ip_address' => request()->ip(),
        ]);

        if ($adminEmail = Setting::get('contact_email')) {
            Mail::to($adminEmail)->send(new EnquiryReceived($enquiry));
        }

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->submitted = true;
    }
};
?>

<div>
    @if ($submitted)
        <div class="alert alert-success">Thank you — your message has been received. We'll get back to you shortly.</div>
    @else
        <form wire:submit="submit" class="enquiry-form">
            <label for="enquiry-website" class="visually-hidden">Leave this field blank</label>
            <input type="text" id="enquiry-website" wire:model="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label for="enquiry-name" class="form-label">Your name <span>*</span></label>
                    <input type="text" id="enquiry-name" placeholder="Full name" class="form-control enquiry-form__control @error('name') is-invalid @enderror" wire:model="name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label for="enquiry-email" class="form-label">Work email <span>*</span></label>
                    <input type="email" id="enquiry-email" placeholder="name@company.com" class="form-control enquiry-form__control @error('email') is-invalid @enderror" wire:model="email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label for="enquiry-phone" class="form-label">Mobile number</label>
                    <input type="text" id="enquiry-phone" placeholder="+91 98765 43210" class="form-control enquiry-form__control @error('phone') is-invalid @enderror" wire:model="phone">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label for="enquiry-subject" class="form-label">I’m interested in</label>
                    <input type="text" id="enquiry-subject" placeholder="Product or application" class="form-control enquiry-form__control @error('subject') is-invalid @enderror" wire:model="subject">
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="enquiry-message" class="form-label">Project requirements <span>*</span></label>
                <textarea id="enquiry-message" placeholder="Tell us about your application, quantity, capacity, or project timeline…" class="form-control enquiry-form__control @error('message') is-invalid @enderror" wire:model="message" rows="5"></textarea>
                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-accent enquiry-form__submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Send enquiry <i class="bi bi-arrow-right"></i></span>
                <span wire:loading>Sending…</span>
            </button>
        </form>
    @endif
</div>
