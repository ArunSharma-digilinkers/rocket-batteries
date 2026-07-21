<?php

use App\Models\Warranty;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

new class extends Component
{
    public string $serialNo = '';
    public string $mobile = '';

    public bool $searched = false;
    public ?Warranty $result = null;

    public function rules(): array
    {
        return [
            'serialNo' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'max:20'],
        ];
    }

    public function lookup(): void
    {
        $this->validate();

        $throttleKey = 'warranty-lookup:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $this->addError('serialNo', 'Too many lookups. Please try again later.');

            return;
        }

        RateLimiter::hit($throttleKey, 600);

        $this->result = Warranty::with('product')
            ->where('serial_no', $this->serialNo)
            ->where('mobile', $this->mobile)
            ->first();

        $this->searched = true;
    }
};
?>

<div>
    <form wire:submit="lookup" class="warranty-form warranty-lookup-form">
        <div class="row g-3 align-items-end">
            <div class="col-md-5 mb-3">
                <label for="lookup-serial" class="form-label">Serial number <span>*</span></label>
                <input type="text" id="lookup-serial" placeholder="Battery serial number" class="form-control warranty-form__control @error('serialNo') is-invalid @enderror" wire:model="serialNo">
                @error('serialNo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-5 mb-3">
                <label for="lookup-mobile" class="form-label">Mobile number <span>*</span></label>
                <input type="text" id="lookup-mobile" placeholder="Number used to register" class="form-control warranty-form__control @error('mobile') is-invalid @enderror" wire:model="mobile">
                @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-accent warranty-lookup-form__submit w-100" wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="bi bi-search"></i> Check</span>
                    <span wire:loading>…</span>
                </button>
            </div>
        </div>
    </form>

    @if ($searched)
        @if ($result)
            @php
                $statusMeta = [
                    'pending' => ['label' => 'Pending Verification', 'class' => 'warning'],
                    'verified' => ['label' => 'Verified', 'class' => 'success'],
                    'rejected' => ['label' => 'Rejected', 'class' => 'danger'],
                ][$result->status] ?? ['label' => ucfirst($result->status), 'class' => 'secondary'];
            @endphp
            <div class="warranty-result mt-2">
                <div class="warranty-result__top">
                    <span class="warranty-result__icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <strong>{{ $result->product->name }}</strong>
                        <small>Serial: {{ $result->serial_no }}</small>
                    </div>
                    <span class="badge bg-{{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
                </div>
                <div class="warranty-result__dates">
                        Registered {{ $result->created_at->format('d M Y') }}
                        @if ($result->verified_at)
                            &middot; Verified {{ $result->verified_at->format('d M Y') }}
                        @endif
                </div>
            </div>
        @else
            <div class="warranty-not-found mt-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div><strong>No warranty found.</strong><span>No warranty found matching that serial number and mobile number. Please double-check and try again.</span></div>
            </div>
        @endif
    @endif
</div>
