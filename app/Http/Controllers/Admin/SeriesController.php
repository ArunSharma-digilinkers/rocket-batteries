<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSeriesRequest;
use App\Http\Requests\Admin\UpdateSeriesRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeriesController extends Controller
{
    public function index(): View
    {
        return view('admin.series.index', [
            'seriesList' => Series::with('category')->withCount('products')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.series.create', [
            'series' => new Series(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        Series::create($data);

        return redirect()->route('admin.series.index')->with('status', 'Series created.');
    }

    public function edit(Series $series): View
    {
        $series->load('attributes');

        return view('admin.series.edit', [
            'series' => $series,
            'categories' => Category::orderBy('name')->get(),
            'allAttributes' => Attribute::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateSeriesRequest $request, Series $series): RedirectResponse
    {
        $data = $request->validated();
        // Leaving slug blank keeps the existing one — renaming shouldn't silently break the URL.
        $data['slug'] = ($data['slug'] ?? null) ?: $series->slug;
        $data['status'] = $request->boolean('status');

        $series->update($data);

        return redirect()->route('admin.series.index')->with('status', 'Series updated.');
    }

    public function destroy(Series $series): RedirectResponse
    {
        if ($series->products()->exists()) {
            return back()->withErrors(['series' => 'Cannot delete a series that has products.']);
        }

        $series->delete();

        return redirect()->route('admin.series.index')->with('status', 'Series deleted.');
    }

    /**
     * Sync the series' spec template: which attributes apply, in what order.
     */
    public function syncAttributes(Request $request, Series $series): RedirectResponse
    {
        $validated = $request->validate([
            'attributes' => ['array'],
            'attributes.*' => ['integer', 'exists:attributes,id'],
            'sort_order' => ['array'],
            'sort_order.*' => ['integer', 'min:0'],
            'required' => ['array'],
            'required.*' => ['integer', 'exists:attributes,id'],
        ]);

        $selectedIds = $validated['attributes'] ?? [];
        $requiredIds = $validated['required'] ?? [];
        $sortOrders = $validated['sort_order'] ?? [];

        $sync = [];
        foreach ($selectedIds as $attributeId) {
            $sync[$attributeId] = [
                'is_required' => in_array($attributeId, $requiredIds),
                'sort_order' => $sortOrders[$attributeId] ?? 0,
            ];
        }

        $series->attributes()->sync($sync);

        return redirect()->route('admin.series.edit', $series)->with('status', 'Spec template updated.');
    }
}
