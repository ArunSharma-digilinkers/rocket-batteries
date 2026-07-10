<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSlugStabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_renaming_a_category_without_touching_slug_keeps_the_url_stable(): void
    {
        $admin = AdminUser::factory()->create();
        $category = Category::factory()->create(['name' => 'EV Batteries', 'slug' => 'ev-batteries']);

        $this->actingAs($admin, 'admin')->put(route('admin.categories.update', $category), [
            'name' => 'EV Batteries Renamed',
            'sort_order' => 1,
            'status' => '1',
            // slug intentionally omitted, as a browser would send it prefilled but this simulates a blank submit
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'EV Batteries Renamed',
            'slug' => 'ev-batteries',
        ]);
    }

    public function test_explicitly_changing_the_slug_is_still_honored(): void
    {
        $admin = AdminUser::factory()->create();
        $category = Category::factory()->create(['name' => 'EV Batteries', 'slug' => 'ev-batteries']);

        $this->actingAs($admin, 'admin')->put(route('admin.categories.update', $category), [
            'name' => 'EV Batteries',
            'slug' => 'ev-batteries-v2',
            'sort_order' => 1,
            'status' => '1',
        ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'slug' => 'ev-batteries-v2']);
    }

    public function test_renaming_a_series_without_touching_slug_keeps_the_url_stable(): void
    {
        $admin = AdminUser::factory()->create();
        $category = Category::factory()->create();
        $series = Series::factory()->create(['category_id' => $category->id, 'name' => 'ES', 'slug' => 'es']);

        $this->actingAs($admin, 'admin')->put(route('admin.series.update', $series), [
            'category_id' => $category->id,
            'name' => 'ES Renamed',
            'sort_order' => 1,
            'status' => '1',
        ]);

        $this->assertDatabaseHas('series', ['id' => $series->id, 'slug' => 'es']);
    }
}
