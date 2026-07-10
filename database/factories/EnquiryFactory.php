<?php

namespace Database\Factories;

use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => null,
            'type' => 'general',
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'message' => $this->faker->sentence(),
            'status' => 'new',
            'source' => 'contact_page',
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
