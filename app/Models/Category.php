<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    public const NAVIGATION_CACHE_KEY = 'nav.categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Active categories with their active series, for the site nav — cached
     * since it's queried on every public page load.
     */
    public static function navigationTree()
    {
        return Cache::remember(self::NAVIGATION_CACHE_KEY, 3600, function () {
            return static::where('status', true)
                ->orderBy('sort_order')
                ->with(['series' => fn ($query) => $query->where('status', true)->orderBy('sort_order')])
                ->get();
        });
    }

    public static function forgetNavigationCache(): void
    {
        Cache::forget(self::NAVIGATION_CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetNavigationCache());
        static::deleted(fn () => static::forgetNavigationCache());
    }
}
