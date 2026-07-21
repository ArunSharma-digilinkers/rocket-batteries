<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Series;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('about', [
            'aboutContent' => Page::where('slug', 'about-us')->first()?->content,
            'stats' => [
                'products' => Product::where('status', true)->count(),
                'series' => Series::where('status', true)->count(),
                'categories' => Category::where('status', true)->count(),
                'applications' => Application::where('status', true)->count(),
            ],
        ]);
    }
}
