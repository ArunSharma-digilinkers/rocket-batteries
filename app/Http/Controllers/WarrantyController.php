<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function index(): View
    {
        return view('warranty', [
            'products' => Product::where('status', true)->orderBy('name')->get(),
        ]);
    }
}
