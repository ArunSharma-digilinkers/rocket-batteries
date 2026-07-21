<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use App\Models\Series;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(): View
    {
        return view('catalog.index', [
            'catalogStats' => [
                'products' => Product::where('status', true)->count(),
                'series' => Series::where('status', true)->count(),
                'categories' => Category::where('status', true)->count(),
                'applications' => Application::where('status', true)->count(),
            ],
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->status, 404);

        $product->load(['series.category', 'series.attributes', 'attributeValues', 'applications', 'media']);

        $relatedProducts = Product::where('series_id', $product->series_id)
            ->where('status', true)
            ->whereKeyNot($product->getKey())
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category): View
    {
        abort_unless($category->status, 404);

        $category->load('series');

        return view('catalog.category', compact('category'));
    }

    public function series(Series $series): View
    {
        abort_unless($series->status, 404);

        $series->load('category');

        return view('catalog.series', compact('series'));
    }
}
