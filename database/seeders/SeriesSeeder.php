<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Series;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
    public function run(): void
    {
        $ev = Category::where('slug', 'ev-batteries')->firstOrFail();
        $stationary = Category::where('slug', 'enerrocket-stationary')->firstOrFail();

        Series::updateOrCreate(
            ['slug' => 'ev'],
            [
                'category_id' => $ev->id,
                'name' => 'EV',
                'description' => 'High-capacity traction batteries rated in Ah with C5/C20 discharge ratings.',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        Series::updateOrCreate(
            ['slug' => 'es'],
            [
                'category_id' => $stationary->id,
                'name' => 'ES',
                'description' => 'Sealed maintenance-free stationary batteries built for telecom, UPS, and industrial backup power.',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        Series::updateOrCreate(
            ['slug' => 'esc'],
            [
                'category_id' => $stationary->id,
                'name' => 'ESC',
                'description' => 'High-performance stationary series engineered for demanding backup and standby applications.',
                'sort_order' => 2,
                'status' => true,
            ]
        );

        Series::updateOrCreate(
            ['slug' => 'esh-tppl'],
            [
                'category_id' => $stationary->id,
                'name' => 'ESH/TPPL',
                'description' => 'Thin Plate Pure Lead technology for extended service life and superior high-rate discharge.',
                'sort_order' => 3,
                'status' => true,
            ]
        );
    }
}
