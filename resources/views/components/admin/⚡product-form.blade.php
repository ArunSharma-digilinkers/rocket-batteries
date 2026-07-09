<?php

use App\Models\Application;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Series;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public ?int $productId = null;

    public string $seriesId = '';
    public string $sku = '';
    public string $name = '';
    public string $slug = '';
    public string $nominalVoltage = '';
    public string $shortDescription = '';
    public string $longDescription = '';
    public bool $isFeatured = false;
    public int $sortOrder = 0;
    public bool $status = true;
    public string $metaTitle = '';
    public string $metaDescription = '';
    public string $metaKeywords = '';

    public array $specValues = [];
    public array $selectedApplications = [];

    public $heroImage = null;
    public $datasheet = null;
    public array $galleryImages = [];

    public function mount(?int $productId = null): void
    {
        $this->productId = $productId;

        if (! $this->productId) {
            return;
        }

        $product = Product::with(['attributeValues', 'applications'])->findOrFail($this->productId);

        $this->seriesId = (string) $product->series_id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->slug = (string) $product->slug;
        $this->nominalVoltage = (string) $product->nominal_voltage;
        $this->shortDescription = (string) $product->short_description;
        $this->longDescription = (string) $product->long_description;
        $this->isFeatured = $product->is_featured;
        $this->sortOrder = $product->sort_order;
        $this->status = $product->status;
        $this->metaTitle = (string) $product->meta_title;
        $this->metaDescription = (string) $product->meta_description;
        $this->metaKeywords = (string) $product->meta_keywords;
        $this->selectedApplications = $product->applications->pluck('id')->map(fn ($id) => (string) $id)->all();

        foreach ($product->attributeValues as $value) {
            $this->specValues[$value->attribute_id] = $value->value;
        }
    }

    #[Computed]
    public function currentProduct(): ?Product
    {
        return $this->productId ? Product::find($this->productId) : null;
    }

    #[Computed]
    public function specAttributes(): Collection
    {
        if (! $this->seriesId) {
            return collect();
        }

        return Series::find($this->seriesId)?->attributes ?? collect();
    }

    #[Computed]
    public function allApplications(): Collection
    {
        return Application::orderBy('sort_order')->get();
    }

    #[Computed]
    public function allSeries(): Collection
    {
        return Series::with('category')->orderBy('name')->get();
    }

    #[Computed]
    public function existingMedia(): Collection
    {
        return $this->currentProduct?->media ?? collect();
    }

    public function updatedSeriesId(): void
    {
        unset($this->specAttributes);

        $validIds = $this->specAttributes->pluck('id')->all();
        $this->specValues = collect($this->specValues)
            ->only($validIds)
            ->all();
    }

    protected function rules(): array
    {
        return [
            'seriesId' => ['required', 'exists:series,id'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,'.$this->productId],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:products,slug,'.$this->productId],
            'nominalVoltage' => ['nullable', 'string', 'max:50'],
            'shortDescription' => ['nullable', 'string'],
            'longDescription' => ['nullable', 'string'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
            'metaTitle' => ['nullable', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:255'],
            'metaKeywords' => ['nullable', 'string', 'max:255'],
            'heroImage' => ['nullable', 'image', 'max:4096'],
            'datasheet' => ['nullable', 'mimes:pdf', 'max:10240'],
            'galleryImages.*' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'series_id' => $this->seriesId,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'nominal_voltage' => $this->nominalVoltage,
            'short_description' => $this->shortDescription,
            'long_description' => $this->longDescription,
            'is_featured' => $this->isFeatured,
            'sort_order' => $this->sortOrder,
            'status' => $this->status,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
        ];

        $product = $this->productId ? Product::findOrFail($this->productId) : new Product();

        if ($this->heroImage) {
            if ($product->hero_image) {
                Storage::disk('public')->delete($product->hero_image);
            }

            $data['hero_image'] = $this->heroImage->store('products', 'public');
        }

        if ($this->datasheet) {
            if ($product->datasheet_path) {
                Storage::disk('public')->delete($product->datasheet_path);
            }

            $data['datasheet_path'] = $this->datasheet->store('products/datasheets', 'public');
        }

        $product->fill($data);
        $product->save();
        $this->productId = $product->id;

        foreach ($this->specAttributes as $attribute) {
            $value = $this->specValues[$attribute->id] ?? null;

            if ($value === null || $value === '') {
                $product->attributeValues()->where('attribute_id', $attribute->id)->delete();

                continue;
            }

            $product->attributeValues()->updateOrCreate(
                ['attribute_id' => $attribute->id],
                ['value' => $value]
            );
        }

        $product->applications()->sync($this->selectedApplications);

        foreach ($this->galleryImages as $image) {
            $path = $image->store('products/gallery', 'public');

            ProductMedia::create([
                'mediable_type' => Product::class,
                'mediable_id' => $product->id,
                'type' => 'image',
                'disk' => 'public',
                'path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'mime_type' => $image->getMimeType(),
                'size' => $image->getSize(),
            ]);
        }

        $this->galleryImages = [];
        $this->heroImage = null;
        $this->datasheet = null;

        unset($this->currentProduct, $this->existingMedia);

        session()->flash('status', 'Product saved.');

        return redirect()->route('admin.products.edit', $product);
    }

    public function deleteMedia(int $mediaId): void
    {
        $media = ProductMedia::findOrFail($mediaId);

        if ($media->mediable_id !== $this->productId || $media->mediable_type !== Product::class) {
            abort(403);
        }

        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        unset($this->existingMedia);
    }
};
?>

<div>
    <form wire:submit="save" enctype="multipart/form-data">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Series</label>
                                <select class="form-select @error('seriesId') is-invalid @enderror" wire:model.live="seriesId">
                                    <option value="">Select a series</option>
                                    @foreach ($this->allSeries as $series)
                                        <option value="{{ $series->id }}">{{ $series->category->name }} — {{ $series->name }}</option>
                                    @endforeach
                                </select>
                                @error('seriesId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Nominal Voltage</label>
                                <input type="text" class="form-control" wire:model="nominalVoltage" placeholder="e.g. 12V">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" wire:model="sku">
                                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug <span class="text-muted small">(auto-generated if left blank)</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" wire:model="slug">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea class="form-control" wire:model="shortDescription" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Long Description</label>
                            <textarea class="form-control" wire:model="longDescription" rows="6"></textarea>
                        </div>
                    </div>
                </div>

                @if ($this->seriesId)
                    <div class="card mb-4">
                        <div class="card-header">Specifications</div>
                        <div class="card-body">
                            @forelse ($this->specAttributes as $attribute)
                                <div class="row mb-3 align-items-center">
                                    <label class="col-sm-4 col-form-label">
                                        {{ $attribute->name }}
                                        @if ($attribute->unit) <span class="text-muted small">({{ $attribute->unit }})</span> @endif
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" wire:model="specValues.{{ $attribute->id }}">
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">This series has no spec template yet. <a href="{{ route('admin.series.edit', $this->seriesId) }}">Configure it here.</a></p>
                            @endforelse
                        </div>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">Applications</div>
                    <div class="card-body">
                        @foreach ($this->allApplications as $application)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" value="{{ $application->id }}" wire:model="selectedApplications">
                                <label class="form-check-label">{{ $application->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">SEO</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-control" wire:model="metaTitle">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-control" wire:model="metaDescription" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" wire:model="metaKeywords">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">Publish</div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" wire:model="status">
                            <label class="form-check-label">Active</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" wire:model="isFeatured">
                            <label class="form-check-label">Featured</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" wire:model="sortOrder">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save Product</span>
                            <span wire:loading>Saving…</span>
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-link w-100">Cancel</a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Hero Image</div>
                    <div class="card-body">
                        @if ($this->currentProduct?->hero_image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($this->currentProduct->hero_image) }}" class="img-fluid mb-2" alt="">
                        @endif
                        <input type="file" class="form-control @error('heroImage') is-invalid @enderror" wire:model="heroImage" accept="image/*">
                        @error('heroImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if ($heroImage)
                            <img src="{{ $heroImage->temporaryUrl() }}" class="img-fluid mt-2" alt="">
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Datasheet (PDF)</div>
                    <div class="card-body">
                        @if ($this->currentProduct?->datasheet_path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($this->currentProduct->datasheet_path) }}" target="_blank">Current datasheet</a>
                        @endif
                        <input type="file" class="form-control @error('datasheet') is-invalid @enderror" wire:model="datasheet" accept="application/pdf">
                        @error('datasheet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Gallery</div>
                    <div class="card-body">
                        @if ($this->productId)
                            <div class="row g-2 mb-3">
                                @foreach ($this->existingMedia as $media)
                                    <div class="col-4 position-relative">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($media->path) }}" class="img-fluid rounded" alt="">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" wire:click="deleteMedia({{ $media->id }})" wire:confirm="Remove this image?">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" class="form-control @error('galleryImages.*') is-invalid @enderror" wire:model="galleryImages" multiple accept="image/*">
                        @error('galleryImages.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
