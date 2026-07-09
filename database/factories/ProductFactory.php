<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = 'RB-' . strtoupper($this->faker->unique()->bothify('??###'));

        return [
            'series_id' => Series::factory(),
            'sku' => $name,
            'name' => $name,
            'slug' => Str::slug($name),
            'nominal_voltage' => $this->faker->randomElement(['2V', '6V', '12V']),
            'short_description' => $this->faker->sentence(),
            'long_description' => $this->faker->paragraphs(3, true),
            'is_featured' => false,
            'sort_order' => 0,
            'status' => true,
        ];
    }
}
