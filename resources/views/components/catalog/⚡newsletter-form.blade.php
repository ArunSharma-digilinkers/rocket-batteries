<?php

use App\Models\Subscriber;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    // Honeypot: real users never fill this in; bots typically do.
    public string $website = '';

    public bool $submitted = false;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function subscribe(): void
    {
        $this->validate();

        if ($this->website !== '') {
            $this->submitted = true;

            return;
        }

        $throttleKey = 'newsletter:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('email', 'Too many attempts. Please try again later.');

            return;
        }

        RateLimiter::hit($throttleKey, 600);

        Subscriber::firstOrCreate(
            ['email' => $this->email],
            ['ip_address' => request()->ip()]
        );

        $this->reset(['email']);
        $this->submitted = true;
    }
};
?>

<div>
    @if ($submitted)
        <div class="text-white small d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-accent"></i> You're subscribed — thanks for joining!
        </div>
    @else
        <form wire:submit="subscribe" class="d-flex flex-column flex-sm-row gap-2">
            <label for="newsletter-website" class="visually-hidden">Leave this field blank</label>
            <input type="text" id="newsletter-website" wire:model="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="flex-grow-1">
                <label for="newsletter-email" class="visually-hidden">Your Email</label>
                <input
                    type="email"
                    id="newsletter-email"
                    placeholder="Your email address"
                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                    wire:model="email"
                >
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-accent rounded-3 px-4 text-nowrap" wire:loading.attr="disabled">
                <span wire:loading.remove>Subscribe</span>
                <span wire:loading>Sending…</span>
            </button>
        </form>
    @endif
</div>
