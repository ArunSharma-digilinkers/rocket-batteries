<?php

use App\Mail\WarrantyReceived;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Warranty;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $customerName = '';
    public string $mobile = '';
    public string $email = '';
    public string $productId = '';
    public string $serialNo = '';
    public string $purchaseDate = '';
    public string $dealerName = '';
    public $invoice = null;

    // Honeypot: real users never fill this in; bots typically do.
    public string $website = '';

    public bool $submitted = false;

    #[Computed]
    public function products()
    {
        return Product::where('status', true)->orderBy('name')->get();
    }

    public function rules(): array
    {
        return [
            'customerName' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'productId' => ['required', 'exists:products,id'],
            'serialNo' => ['required', 'string', 'max:100'],
            'purchaseDate' => ['required', 'date', 'before_or_equal:today'],
            'dealerName' => ['nullable', 'string', 'max:255'],
            'invoice' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->website !== '') {
            $this->submitted = true;

            return;
        }

        $throttleKey = 'warranty:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('customerName', 'Too many registrations submitted. Please try again later.');

            return;
        }

        RateLimiter::hit($throttleKey, 600);

        $invoicePath = $this->invoice?->store('warranties', 'public');

        $warranty = Warranty::create([
            'product_id' => $this->productId,
            'customer_name' => $this->customerName,
            'mobile' => $this->mobile,
            'email' => $this->email ?: null,
            'serial_no' => $this->serialNo,
            'purchase_date' => $this->purchaseDate,
            'dealer_name' => $this->dealerName ?: null,
            'invoice_path' => $invoicePath,
            'status' => 'pending',
        ]);

        if ($adminEmail = Setting::get('contact_email')) {
            Mail::to($adminEmail)->send(new WarrantyReceived($warranty->load('product')));
        }

        $this->reset(['customerName', 'mobile', 'email', 'productId', 'serialNo', 'purchaseDate', 'dealerName', 'invoice']);
        $this->submitted = true;
    }
};
?>

<div>
    @if ($submitted)
        <div class="warranty-success">
            <i class="bi bi-check-circle-fill me-1"></i>
            <div><strong>Registration received.</strong><span>Your warranty is pending verification. You can check its status anytime using the lookup form.</span></div>
        </div>
    @else
        <form wire:submit="submit" enctype="multipart/form-data" class="warranty-form">
            <label for="warranty-website" class="visually-hidden">Leave this field blank</label>
            <input type="text" id="warranty-website" wire:model="website" class="d-none" tabindex="-1" autocomplete="off">

            <div class="row g-3 mb-3">
                <div class="col-sm-6 mb-3">
                    <label for="warranty-name" class="form-label">Full name <span>*</span></label>
                    <input type="text" id="warranty-name" placeholder="Name on purchase" class="form-control warranty-form__control @error('customerName') is-invalid @enderror" wire:model="customerName">
                    @error('customerName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="warranty-mobile" class="form-label">Mobile number <span>*</span></label>
                    <input type="text" id="warranty-mobile" placeholder="+91 98765 43210" class="form-control warranty-form__control @error('mobile') is-invalid @enderror" wire:model="mobile">
                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="warranty-email" class="form-label">Email <small>Optional</small></label>
                <input type="email" id="warranty-email" placeholder="name@example.com" class="form-control warranty-form__control @error('email') is-invalid @enderror" wire:model="email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="warranty-product" class="form-label">Product <span>*</span></label>
                <select id="warranty-product" class="form-select warranty-form__control @error('productId') is-invalid @enderror" wire:model="productId">
                    <option value="">Select a product</option>
                    @foreach ($this->products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                    @endforeach
                </select>
                @error('productId') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6 mb-3">
                    <label for="warranty-serial" class="form-label">Serial number <span>*</span></label>
                    <input type="text" id="warranty-serial" placeholder="Battery serial number" class="form-control warranty-form__control @error('serialNo') is-invalid @enderror" wire:model="serialNo">
                    @error('serialNo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    <label for="warranty-purchase-date" class="form-label">Purchase date <span>*</span></label>
                    <input type="date" id="warranty-purchase-date" class="form-control warranty-form__control @error('purchaseDate') is-invalid @enderror" wire:model="purchaseDate">
                    @error('purchaseDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="warranty-dealer" class="form-label">Dealer name <small>Optional</small></label>
                <input type="text" id="warranty-dealer" placeholder="Where you purchased the battery" class="form-control warranty-form__control @error('dealerName') is-invalid @enderror" wire:model="dealerName">
                @error('dealerName') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="warranty-invoice" class="form-label">Invoice / proof of purchase <small>Optional · PDF or image · Max 5MB</small></label>
                <input type="file" id="warranty-invoice" class="form-control warranty-form__control warranty-form__file @error('invoice') is-invalid @enderror" wire:model="invoice" accept=".pdf,.jpg,.jpeg,.png">
                @error('invoice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div wire:loading wire:target="invoice" class="small text-muted mt-1">Uploading…</div>
            </div>

            <button type="submit" class="btn btn-accent warranty-form__submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Register warranty <i class="bi bi-arrow-right"></i></span>
                <span wire:loading wire:target="submit">Submitting…</span>
            </button>
        </form>
    @endif
</div>
