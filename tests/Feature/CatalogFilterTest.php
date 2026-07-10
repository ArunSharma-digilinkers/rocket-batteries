<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attribute;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    private Series $evSeries;
    private Series $esSeries;
    private Product $ev100;
    private Product $ev150;
    private Product $es40;
    private Attribute $capacityAttribute;
    private Application $ups;

    protected function setUp(): void
    {
        parent::setUp();

        $this->evSeries = Series::factory()->create(['slug' => 'ev']);
        $this->esSeries = Series::factory()->create(['slug' => 'es']);

        $this->capacityAttribute = Attribute::factory()->create([
            'slug' => 'capacity-ah',
            'is_filterable' => true,
        ]);
        $this->evSeries->attributes()->attach($this->capacityAttribute->id, ['sort_order' => 1]);

        $this->ups = Application::factory()->create(['name' => 'UPS']);

        $this->ev100 = Product::factory()->create(['series_id' => $this->evSeries->id, 'sku' => 'RB-EV100', 'name' => 'RB-EV100']);
        $this->ev100->attributeValues()->create(['attribute_id' => $this->capacityAttribute->id, 'value' => '100']);

        $this->ev150 = Product::factory()->create(['series_id' => $this->evSeries->id, 'sku' => 'RB-EV150', 'name' => 'RB-EV150']);
        $this->ev150->attributeValues()->create(['attribute_id' => $this->capacityAttribute->id, 'value' => '150']);

        $this->es40 = Product::factory()->create(['series_id' => $this->esSeries->id, 'sku' => 'RB-ES40', 'name' => 'RB-ES40']);
        $this->es40->applications()->attach($this->ups->id);
    }

    public function test_product_list_filters_by_series(): void
    {
        Livewire::test('catalog.product-list')
            ->assertSee('RB-EV100')
            ->assertSee('RB-ES40')
            ->set('series', [$this->evSeries->id])
            ->assertSee('RB-EV100')
            ->assertDontSee('RB-ES40');
    }

    public function test_product_list_filters_by_application(): void
    {
        Livewire::test('catalog.product-list')
            ->set('applications', [$this->ups->id])
            ->assertSee('RB-ES40')
            ->assertDontSee('RB-EV100');
    }

    public function test_product_list_filters_by_search(): void
    {
        Livewire::test('catalog.product-list')
            ->set('search', 'RB-ES40')
            ->assertSee('RB-ES40')
            ->assertDontSee('RB-EV100');
    }

    public function test_product_list_filters_by_filterable_spec_value(): void
    {
        Livewire::test('catalog.product-list')
            ->set("specs.{$this->capacityAttribute->id}", ['100'])
            ->assertSee('RB-EV100')
            ->assertDontSee('RB-EV150');
    }

    public function test_request_quote_form_creates_an_enquiry(): void
    {
        Livewire::test('catalog.request-quote-form', ['productId' => $this->es40->id])
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('message', 'Please send pricing.')
            ->call('submit')
            ->assertSee('Thank you');

        $this->assertTrue(
            Enquiry::where('email', 'buyer@example.com')->where('product_id', $this->es40->id)->exists()
        );
    }

    public function test_request_quote_form_honeypot_silently_drops_submission(): void
    {
        Livewire::test('catalog.request-quote-form', ['productId' => $this->es40->id])
            ->set('name', 'Bot')
            ->set('email', 'bot@example.com')
            ->set('website', 'http://spam.example')
            ->call('submit');

        $this->assertFalse(Enquiry::where('email', 'bot@example.com')->exists());
    }
}
