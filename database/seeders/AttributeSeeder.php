<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Series;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Attribute definitions, keyed by slug, with the series slugs whose spec
     * template they belong to (order in the array is the display order).
     */
    public function run(): void
    {
        $definitions = [
            // EV series: capacity + C-rate discharge ratings.
            'capacity-ah' => ['name' => 'Capacity', 'unit' => 'Ah', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => true, 'series' => ['ev']],
            'c5-rate' => ['name' => 'C5 Rate', 'unit' => 'A', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['ev']],
            'c20-rate' => ['name' => 'C20 Rate', 'unit' => 'A', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['ev']],

            // ES / ESC series: hour-rate discharge ratings + electrical/environmental limits.
            '20hr-rate' => ['name' => '20-Hr Rate', 'unit' => 'Ah', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => true, 'series' => ['es', 'esc']],
            '10hr-rate' => ['name' => '10-Hr Rate', 'unit' => 'Ah', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['es', 'esc']],
            '5hr-rate' => ['name' => '5-Hr Rate', 'unit' => 'Ah', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['es', 'esc']],
            'internal-resistance' => ['name' => 'Internal Resistance', 'unit' => 'mΩ', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['es', 'esc']],
            'operating-temperature' => ['name' => 'Operating Temperature', 'unit' => '°C', 'group' => 'Environmental', 'data_type' => 'text', 'is_filterable' => false, 'series' => ['es', 'esc', 'esh-tppl']],
            'max-discharge-current' => ['name' => 'Max Discharge Current', 'unit' => 'A', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['es', 'esc']],

            // ESH/TPPL series: power (watts) rated for high-rate backup.
            'watts-per-cell' => ['name' => 'Watts per Cell', 'unit' => 'W', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => true, 'series' => ['esh-tppl']],
            'backup-time' => ['name' => 'Backup Time', 'unit' => 'min', 'group' => 'Electrical', 'data_type' => 'number', 'is_filterable' => false, 'series' => ['esh-tppl']],
        ];

        $seriesBySlug = Series::pluck('id', 'slug');
        $sortOrderBySeries = [];

        foreach ($definitions as $slug => $definition) {
            $attribute = Attribute::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $definition['name'],
                    'unit' => $definition['unit'],
                    'group' => $definition['group'],
                    'data_type' => $definition['data_type'],
                    'is_filterable' => $definition['is_filterable'],
                    'sort_order' => 0,
                ]
            );

            foreach ($definition['series'] as $seriesSlug) {
                $seriesId = $seriesBySlug[$seriesSlug] ?? null;

                if (! $seriesId) {
                    continue;
                }

                $sortOrderBySeries[$seriesSlug] = ($sortOrderBySeries[$seriesSlug] ?? 0) + 1;

                Series::find($seriesId)->attributes()->syncWithoutDetaching([
                    $attribute->id => [
                        'is_required' => true,
                        'sort_order' => $sortOrderBySeries[$seriesSlug],
                    ],
                ]);
            }
        }
    }
}
