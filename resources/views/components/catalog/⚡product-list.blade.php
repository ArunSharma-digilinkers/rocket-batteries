<?php

use App\Models\Application;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\Series;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public array $series = [];

    #[Url]
    public array $applications = [];

    #[Url]
    public array $voltages = [];

    #[Url]
    public array $specs = [];

    public function mount(?array $initialSeries = null, ?int $initialCategory = null): void
    {
        if ($initialCategory) {
            $this->series = Series::where('category_id', $initialCategory)->pluck('id')->map(fn ($id) => (string) $id)->all();
        }

        if ($initialSeries) {
            $this->series = array_map('strval', $initialSeries);
        }
    }

    public function updating($property): void
    {
        if (in_array($property, ['search', 'series', 'applications', 'voltages', 'specs'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'series', 'applications', 'voltages', 'specs']);
        $this->resetPage();
    }

    #[Computed]
    public function products()
    {
        $query = Product::query()
            ->where('status', true)
            ->with(['series.category', 'applications'])
            ->orderBy('sort_order');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            });
        }

        if (! empty($this->series)) {
            $query->whereIn('series_id', $this->series);
        }

        if (! empty($this->applications)) {
            $query->whereHas('applications', function ($q) {
                $q->whereIn('applications.id', $this->applications);
            });
        }

        if (! empty($this->voltages)) {
            $query->whereIn('nominal_voltage', $this->voltages);
        }

        foreach ($this->specs as $attributeId => $values) {
            if (empty($values)) {
                continue;
            }

            $query->whereHas('attributeValues', function ($q) use ($attributeId, $values) {
                $q->where('attribute_id', $attributeId)->whereIn('value', $values);
            });
        }

        return $query->paginate(9);
    }

    #[Computed]
    public function seriesOptions(): Collection
    {
        return Series::where('status', true)->with('category')->orderBy('name')->get();
    }

    #[Computed]
    public function applicationOptions(): Collection
    {
        return Application::where('status', true)->orderBy('sort_order')->get();
    }

    #[Computed]
    public function voltageOptions(): Collection
    {
        return Product::where('status', true)->whereNotNull('nominal_voltage')
            ->distinct()->orderBy('nominal_voltage')->pluck('nominal_voltage');
    }

    #[Computed]
    public function specFilterAttributes(): Collection
    {
        return Attribute::where('is_filterable', true)->orderBy('name')->get()->map(function ($attribute) {
            $attribute->availableValues = ProductAttributeValue::where('attribute_id', $attribute->id)
                ->distinct()->orderBy('value')->pluck('value');

            return $attribute;
        })->filter(fn ($attribute) => $attribute->availableValues->isNotEmpty());
    }
};
?>

<div>
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Filters
                    <button type="button" class="btn btn-sm btn-link p-0" wire:click="resetFilters">Reset</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" wire:model.live.debounce.400ms="search" placeholder="Name or SKU">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Series</label>
                        @foreach ($this->seriesOptions as $option)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="{{ $option->id }}" wire:model.live="series">
                                <label class="form-check-label">{{ $option->category->name }} — {{ $option->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Application</label>
                        @foreach ($this->applicationOptions as $option)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="{{ $option->id }}" wire:model.live="applications">
                                <label class="form-check-label">{{ $option->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal Voltage</label>
                        @foreach ($this->voltageOptions as $option)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="{{ $option }}" wire:model.live="voltages">
                                <label class="form-check-label">{{ $option }}</label>
                            </div>
                        @endforeach
                    </div>

                    @foreach ($this->specFilterAttributes as $attribute)
                        <div class="mb-3">
                            <label class="form-label">{{ $attribute->name }} @if ($attribute->unit)<span class="text-muted small">({{ $attribute->unit }})</span>@endif</label>
                            @foreach ($attribute->availableValues as $value)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $value }}" wire:model.live="specs.{{ $attribute->id }}">
                                    <label class="form-check-label">{{ $value }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted small">{{ $this->products->total() }} products found</div>
            </div>

            <div class="row g-4" wire:loading.class="opacity-50">
                @forelse ($this->products as $product)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100">
                            @if ($product->hero_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($product->hero_image) }}" class="card-img-top" alt="{{ $product->name }}" loading="lazy">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small">{{ $product->series->category->name }} — {{ $product->series->name }}</div>
                                <h3 class="h5 card-title">{{ $product->name }}</h3>
                                <p class="card-text text-muted small flex-grow-1">{{ $product->short_description }}</p>
                                @if ($product->applications->isNotEmpty())
                                    <div class="mb-2">
                                        @foreach ($product->applications as $application)
                                            <span class="badge bg-secondary">{{ $application->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary mt-auto">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted text-center py-5">No products match your filters.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $this->products->links() }}
            </div>
        </div>
    </div>
</div>
