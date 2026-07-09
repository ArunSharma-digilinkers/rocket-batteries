<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('series.category')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->media as $media) {
            Storage::disk($media->disk)->delete($media->path);
            $media->delete();
        }

        if ($product->hero_image) {
            Storage::disk('public')->delete($product->hero_image);
        }

        if ($product->datasheet_path) {
            Storage::disk('public')->delete($product->datasheet_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }
}
