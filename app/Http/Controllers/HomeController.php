<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Client;
use App\Models\NewsEvent;
use App\Models\Product;
use App\Models\Series;
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
            'otherSeries' => Series::whereHas('category', fn ($q) => $q->where('slug', 'enerrocket-stationary'))
                ->where('status', true)->orderBy('sort_order')->get(),
            'newsEvents' => NewsEvent::where('status', true)->orderBy('event_date', 'desc')->take(2)->get(),
            'evSpecs' => $evSpecs = $this->evSpecs(),
            'heroStats' => [
                'years' => (now()->year - 1952).'+',
                'ev_series' => count($evSpecs),
                'applications' => Application::where('status', true)->count(),
            ],
        ]);
    }

    /**
     * Real EV series spec sheet, shown on the home page's product tab switcher.
     * Not tied to catalog Product records yet — see project notes on syncing
     * this into the actual EV series products.
     */
    private function evSpecs(): array
    {
        return [
            'EV-3200G' => ['c5' => '30 Ah', 'c20' => '32 Ah', 'dimensions' => '181 × 77 × 170 mm', 'total_height' => '170 mm', 'weight' => '7.05 Kg', 'max_charge' => '≤ 3A', 'image' => 'img/ev-3200.png'],
            'EV-4400' => ['c5' => '40 Ah', 'c20' => '44 Ah', 'dimensions' => '267 × 77 × 170 mm', 'total_height' => '170 mm', 'weight' => '9.7 Kg', 'max_charge' => '≤ 4A', 'image' => 'img/ev-4400.png'],
            'EV-5200' => ['c5' => '52 Ah', 'c20' => '60 Ah', 'dimensions' => '224 × 120 × 174 mm', 'total_height' => '175 mm', 'weight' => '12.4 Kg', 'max_charge' => '≤ 5A', 'image' => 'img/ev-5200.png'],
            'EV-7000' => ['c5' => '60 Ah', 'c20' => '70 Ah', 'dimensions' => '288 × 148 × 178 mm', 'total_height' => '178 mm', 'weight' => '15.6 Kg', 'max_charge' => '≤ 7A', 'image' => 'img/ev-7000.png'],
        ];
    }
}
