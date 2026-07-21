<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warranty>
 */
class WarrantyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'customer_name' => $this->faker->name(),
            'mobile' => $this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail(),
            'serial_no' => strtoupper($this->faker->bothify('SN-????-####')),
            'purchase_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'dealer_name' => $this->faker->company(),
            'status' => 'pending',
        ];
    }
}
