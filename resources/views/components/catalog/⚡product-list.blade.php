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

    #[Computed]
    public function activeFilterCount(): int
    {
        return ($this->search !== '' ? 1 : 0)
            + count($this->series)
            + count($this->applications)
            + count($this->voltages)
            + collect($this->specs)->sum(fn ($values) => count((array) $values));
    }
};
?>

<div class="catalog-live" x-data="{ filtersOpen: false }">
    <div class="catalog-toolbar">
        <div class="catalog-search">
            <i class="bi bi-search"></i>
            <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search by product name or SKU" aria-label="Search products">
            <span wire:loading wire:target="search" class="catalog-search__loading"><span class="spinner-border spinner-border-sm"></span></span>
        </div>
        <button type="button" class="catalog-filter-toggle" @click="filtersOpen = !filtersOpen" :aria-expanded="filtersOpen.toString()">
            <i class="bi bi-sliders2"></i> Filters
            @if ($this->activeFilterCount)
                <span>{{ $this->activeFilterCount }}</span>
            @endif
        </button>
        <div class="catalog-toolbar__result">
            <strong>{{ $this->products->total() }}</strong>
            <span>{{ Str::plural('product', $this->products->total()) }} found</span>
        </div>
    </div>

    <div class="catalog-layout">
        <aside class="catalog-filters" :class="filtersOpen ? 'is-open' : ''">
            <div class="catalog-filters__head">
                <div><span>Refine results</span><strong>Product filters</strong></div>
                @if ($this->activeFilterCount)
                    <button type="button" wire:click="resetFilters">Clear all</button>
                @endif
            </div>

            <div class="catalog-filter-group">
                <div class="catalog-filter-group__title">
                    <span><i class="bi bi-collection-fill"></i> Battery series</span>
                    <small>{{ $this->seriesOptions->count() }}</small>
                </div>
                <div class="catalog-filter-group__options">
                    @foreach ($this->seriesOptions as $option)
                        <label class="catalog-check" for="series-{{ $option->id }}">
                            <input id="series-{{ $option->id }}" type="checkbox" value="{{ $option->id }}" wire:model.live="series">
                            <span class="catalog-check__box"><i class="bi bi-check"></i></span>
                            <span><strong>{{ $option->name }}</strong><small>{{ $option->category->name }}</small></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="catalog-filter-group">
                <div class="catalog-filter-group__title">
                    <span><i class="bi bi-grid-fill"></i> Application</span>
                    <small>{{ $this->applicationOptions->count() }}</small>
                </div>
                <div class="catalog-filter-group__options catalog-filter-group__options--compact">
                    @foreach ($this->applicationOptions as $option)
                        <label class="catalog-check" for="application-{{ $option->id }}">
                            <input id="application-{{ $option->id }}" type="checkbox" value="{{ $option->id }}" wire:model.live="applications">
                            <span class="catalog-check__box"><i class="bi bi-check"></i></span>
                            <span><strong>{{ $option->name }}</strong></span>
                        </label>
                    @endforeach
                </div>
            </div>

            @if ($this->voltageOptions->isNotEmpty())
                <div class="catalog-filter-group">
                    <div class="catalog-filter-group__title">
                        <span><i class="bi bi-lightning-charge-fill"></i> Nominal voltage</span>
                        <small>{{ $this->voltageOptions->count() }}</small>
                    </div>
                    <div class="catalog-filter-pills">
                        @foreach ($this->voltageOptions as $option)
                            @php $voltageId = 'voltage-'.md5((string) $option); @endphp
                            <label for="{{ $voltageId }}">
                                <input id="{{ $voltageId }}" type="checkbox" value="{{ $option }}" wire:model.live="voltages">
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @foreach ($this->specFilterAttributes as $attribute)
                <div class="catalog-filter-group">
                    <div class="catalog-filter-group__title">
                        <span><i class="bi bi-toggles"></i> {{ $attribute->name }}</span>
                        @if ($attribute->unit)<small>{{ $attribute->unit }}</small>@endif
                    </div>
                    <div class="catalog-filter-pills">
                        @foreach ($attribute->availableValues as $value)
                            @php $specId = 'spec-'.$attribute->id.'-'.md5((string) $value); @endphp
                            <label for="{{ $specId }}">
                                <input id="{{ $specId }}" type="checkbox" value="{{ $value }}" wire:model.live="specs.{{ $attribute->id }}">
                                <span>{{ $value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="catalog-filters__help">
                <span><i class="bi bi-headset"></i></span>
                <div><strong>Not sure what fits?</strong><small>Our team can help match your requirements.</small><a href="{{ route('contact') }}">Ask an expert <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </aside>

        <main class="catalog-results">
            @if ($this->activeFilterCount)
                <div class="catalog-active-filters">
                    <span><i class="bi bi-funnel-fill"></i> {{ $this->activeFilterCount }} active {{ Str::plural('filter', $this->activeFilterCount) }}</span>
                    <button type="button" wire:click="resetFilters">Reset filters</button>
                </div>
            @endif

            <div class="catalog-product-grid" wire:loading.class="is-loading">
                @php
                    $fallbackImages = ['img/ev-3200.png', 'img/ev-4400.png', 'img/ev-5200.png', 'img/ev-7000.png'];
                @endphp
                @forelse ($this->products as $product)
                    <article class="catalog-product-card">
                        <a href="{{ route('products.show', $product) }}" class="catalog-product-card__visual" aria-label="View {{ $product->name }} details">
                            <span class="catalog-product-card__series">{{ $product->series->name }}</span>
                            <span class="catalog-product-card__grid" aria-hidden="true"></span>
                            <img
                                src="{{ $product->hero_image ? \Illuminate\Support\Facades\Storage::url($product->hero_image) : asset($fallbackImages[$loop->index % count($fallbackImages)]) }}"
                                alt="{{ $product->name }}"
                                loading="lazy"
                            >
                            <span class="catalog-product-card__view"><i class="bi bi-arrow-up-right"></i></span>
                        </a>
                        <div class="catalog-product-card__body">
                            <div class="catalog-product-card__meta">
                                <span>{{ $product->series->category->name }}</span>
                                @if ($product->nominal_voltage)<span><i class="bi bi-lightning-charge-fill"></i> {{ $product->nominal_voltage }}</span>@endif
                            </div>
                            <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
                            <span class="catalog-product-card__sku">SKU · {{ $product->sku }}</span>
                            <p>{{ $product->short_description ?: 'Dependable Rocket battery performance engineered for demanding applications.' }}</p>
                            @if ($product->applications->isNotEmpty())
                                <div class="catalog-product-card__applications">
                                    @foreach ($product->applications->take(3) as $application)
                                        <span>{{ $application->name }}</span>
                                    @endforeach
                                    @if ($product->applications->count() > 3)
                                        <span>+{{ $product->applications->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ route('products.show', $product) }}" class="catalog-product-card__link">View specifications <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </article>
                @empty
                    <div class="catalog-empty">
                        <span><i class="bi bi-search"></i></span>
                        <h3>No matching products found.</h3>
                        <p>Try a broader search or clear some filters to explore more of the Rocket range.</p>
                        <button type="button" class="btn btn-accent" wire:click="resetFilters">Clear all filters</button>
                    </div>
                @endforelse
            </div>

            @if ($this->products->hasPages())
                <div class="catalog-pagination">
                    {{ $this->products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
