<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Series;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = Cache::remember('sitemap.urls', 3600, function () {
            $urls = collect([
                ['loc' => route('home'), 'priority' => '1.0'],
                ['loc' => route('products.index'), 'priority' => '0.9'],
                ['loc' => route('contact'), 'priority' => '0.5'],
            ]);

            Category::where('status', true)->get()->each(function (Category $category) use ($urls) {
                $urls->push(['loc' => route('categories.show', $category), 'priority' => '0.7']);
            });

            Series::where('status', true)->get()->each(function (Series $series) use ($urls) {
                $urls->push(['loc' => route('series.show', $series), 'priority' => '0.7']);
            });

            Product::where('status', true)->get()->each(function (Product $product) use ($urls) {
                $urls->push([
                    'loc' => route('products.show', $product),
                    'priority' => '0.8',
                    'lastmod' => $product->updated_at->toAtomString(),
                ]);
            });

            return $urls;
        });

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
