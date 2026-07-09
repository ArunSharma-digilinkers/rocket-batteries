<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Demo catalog data — placeholder specs matching each series' real
     * structure (not client-supplied figures; replace once real data lands).
     */
    public function run(): void
    {
        $catalog = [
            'ev' => [
                'voltage' => '12V',
                'applications' => ['EV'],
                'products' => [
                    ['sku' => 'RB-EV100', 'specs' => ['capacity-ah' => '100', 'c5-rate' => '20', 'c20-rate' => '5']],
                    ['sku' => 'RB-EV150', 'specs' => ['capacity-ah' => '150', 'c5-rate' => '30', 'c20-rate' => '7.5']],
                ],
            ],
            'es' => [
                'voltage' => '12V',
                'applications' => ['UPS', 'Telecom'],
                'products' => [
                    ['sku' => 'RB-ES40', 'specs' => ['20hr-rate' => '40', '10hr-rate' => '37', '5hr-rate' => '32', 'internal-resistance' => '8', 'operating-temperature' => '-15 to 50', 'max-discharge-current' => '400']],
                    ['sku' => 'RB-ES100', 'specs' => ['20hr-rate' => '100', '10hr-rate' => '92', '5hr-rate' => '80', 'internal-resistance' => '5', 'operating-temperature' => '-15 to 50', 'max-discharge-current' => '900']],
                ],
            ],
            'esc' => [
                'voltage' => '12V',
                'applications' => ['UPS', 'Solar'],
                'products' => [
                    ['sku' => 'RB-ESC20', 'specs' => ['20hr-rate' => '20', '10hr-rate' => '18', '5hr-rate' => '16', 'internal-resistance' => '12', 'operating-temperature' => '-15 to 50', 'max-discharge-current' => '220']],
                    ['sku' => 'RB-ESC65', 'specs' => ['20hr-rate' => '65', '10hr-rate' => '60', '5hr-rate' => '52', 'internal-resistance' => '6', 'operating-temperature' => '-15 to 50', 'max-discharge-current' => '600']],
                ],
            ],
            'esh-tppl' => [
                'voltage' => '12V',
                'applications' => ['UPS', 'Medical', 'Fire/Safety'],
                'products' => [
                    ['sku' => 'RB-ESH500', 'specs' => ['watts-per-cell' => '500', 'backup-time' => '15', 'operating-temperature' => '-15 to 60']],
                    ['sku' => 'RB-ESH1000', 'specs' => ['watts-per-cell' => '1000', 'backup-time' => '15', 'operating-temperature' => '-15 to 60']],
                ],
            ],
        ];

        $attributesBySlug = Attribute::pluck('id', 'slug');
        $applicationsByName = Application::pluck('id', 'name');

        foreach ($catalog as $seriesSlug => $seriesData) {
            $series = Series::where('slug', $seriesSlug)->first();

            if (! $series) {
                continue;
            }

            foreach ($seriesData['products'] as $index => $productData) {
                $product = Product::updateOrCreate(
                    ['sku' => $productData['sku']],
                    [
                        'series_id' => $series->id,
                        'name' => $productData['sku'],
                        'slug' => Str::slug($productData['sku']),
                        'nominal_voltage' => $seriesData['voltage'],
                        'short_description' => "{$series->name} series battery — demo catalog entry.",
                        'long_description' => "Placeholder long description for {$productData['sku']}. Replace with real client-supplied copy before launch.",
                        'is_featured' => $index === 0,
                        'sort_order' => $index + 1,
                        'status' => true,
                        'meta_title' => "{$productData['sku']} | Rocket Batteries",
                        'meta_description' => "Specifications and datasheet for {$productData['sku']}.",
                    ]
                );

                foreach ($productData['specs'] as $attributeSlug => $value) {
                    $attributeId = $attributesBySlug[$attributeSlug] ?? null;

                    if (! $attributeId) {
                        continue;
                    }

                    $product->attributeValues()->updateOrCreate(
                        ['attribute_id' => $attributeId],
                        ['value' => $value]
                    );
                }

                $applicationIds = collect($seriesData['applications'])
                    ->map(fn ($name) => $applicationsByName[$name] ?? null)
                    ->filter()
                    ->values();

                $product->applications()->syncWithoutDetaching($applicationIds);
            }
        }
    }
}
