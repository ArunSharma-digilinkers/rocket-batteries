<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 'ev-batteries'],
            [
                'name' => 'EV Batteries',
                'description' => 'Deep-cycle traction batteries for electric vehicles.',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'enerrocket-stationary'],
            [
                'name' => 'EnerRocket Stationary',
                'description' => 'Stationary batteries for UPS, solar, telecom and standby power.',
                'sort_order' => 2,
                'status' => true,
            ]
        );
    }
}
