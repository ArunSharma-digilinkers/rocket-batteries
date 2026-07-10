<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml_with_expected_urls(): void
    {
        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id, 'status' => true]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee(route('home'), false);
        $response->assertSee(route('products.show', $product), false);
        $response->assertSee(route('series.show', $series), false);
    }

    public function test_sitemap_excludes_inactive_products(): void
    {
        $series = Series::factory()->create();
        $hiddenProduct = Product::factory()->create(['series_id' => $series->id, 'status' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertDontSee(route('products.show', $hiddenProduct), false);
    }

    public function test_navigation_cache_invalidates_when_category_is_saved(): void
    {
        $category = Category::factory()->create(['name' => 'Original Name', 'status' => true]);

        $tree = Category::navigationTree();
        $this->assertTrue($tree->contains('name', 'Original Name'));

        $category->update(['name' => 'Renamed']);

        $freshTree = Category::navigationTree();
        $this->assertTrue($freshTree->contains('name', 'Renamed'));
        $this->assertFalse($freshTree->contains('name', 'Original Name'));
    }

    public function test_navigation_cache_invalidates_when_category_is_deleted(): void
    {
        $category = Category::factory()->create(['status' => true]);

        Category::navigationTree();
        $category->delete();

        $this->assertFalse(Cache::has(Category::NAVIGATION_CACHE_KEY));
    }

    public function test_home_page_includes_organization_structured_data(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('"@type":"Organization"', false);
    }

    public function test_product_page_includes_product_structured_data(): void
    {
        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id, 'status' => true]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('"@type":"Product"', false);
        $response->assertSee($product->sku, false);
    }
}
