<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Client;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'sliders' => Slider::where('status', true)->orderBy('sort_order')->get(),
            'featuredProducts' => Product::where('status', true)->where('is_featured', true)
                ->with('series.category')->orderBy('sort_order')->take(6)->get(),
            'applications' => Application::where('status', true)->orderBy('sort_order')->get(),
            'clients' => Client::where('status', true)->orderBy('sort_order')->get(),
            'testimonials' => Testimonial::where('status', true)->orderBy('sort_order')->get(),
        ]);
    }
}
