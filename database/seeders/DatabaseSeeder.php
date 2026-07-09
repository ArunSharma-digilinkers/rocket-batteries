<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Local-only: demo catalog + CMS content, never run in production.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command?->warn('Skipping DatabaseSeeder: seeders are local-only.');

            return;
        }

        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            SeriesSeeder::class,
            AttributeSeeder::class,
            ApplicationSeeder::class,
            ProductSeeder::class,
            CmsSeeder::class,
        ]);
    }
}
