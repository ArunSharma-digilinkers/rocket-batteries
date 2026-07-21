<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'application_product');
    }

    /**
     * Bootstrap Icons class for this application. Uses the admin-set `icon`
     * field if present, otherwise a sensible default keyed by name.
     */
    public function getIconClassAttribute(): string
    {
        if ($this->attributes['icon'] ?? null) {
            return $this->attributes['icon'];
        }

        return match (true) {
            str_contains(strtolower($this->name), 'ups') => 'bi-lightning-charge-fill',
            str_contains(strtolower($this->name), 'solar') => 'bi-sun-fill',
            str_contains(strtolower($this->name), 'telecom') => 'bi-broadcast-pin',
            str_contains(strtolower($this->name), 'ev') => 'bi-ev-station-fill',
            str_contains(strtolower($this->name), 'medical') => 'bi-heart-pulse-fill',
            str_contains(strtolower($this->name), 'fire'), str_contains(strtolower($this->name), 'safety') => 'bi-shield-fill-check',
            default => 'bi-battery-charging',
        };
    }
}
