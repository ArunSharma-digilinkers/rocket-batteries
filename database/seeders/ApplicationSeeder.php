<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $applications = ['UPS', 'Solar', 'Telecom', 'EV', 'Medical', 'Fire/Safety'];

        foreach ($applications as $index => $name) {
            Application::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Batteries suited for {$name} applications.",
                    'sort_order' => $index + 1,
                    'status' => true,
                ]
            );
        }
    }
}
