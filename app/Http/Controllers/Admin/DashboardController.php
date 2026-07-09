<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Series;
use App\Models\Warranty;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'categories' => Category::count(),
                'series' => Series::count(),
                'products' => Product::count(),
                'applications' => Application::count(),
                'enquiries' => Enquiry::where('status', 'new')->count(),
                'warranties' => Warranty::where('status', 'pending')->count(),
            ],
        ]);
    }
}
