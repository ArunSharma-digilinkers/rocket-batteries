<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\UpdateAttributeRequest;
use App\Models\Attribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        return view('admin.attributes.index', [
            'attributes' => Attribute::orderBy('group')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.attributes.create', ['attribute' => new Attribute()]);
    }

    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_filterable'] = $request->boolean('is_filterable');
        $data['options'] = $this->parseOptions($data['options'] ?? null);

        Attribute::create($data);

        return redirect()->route('admin.attributes.index')->with('status', 'Attribute created.');
    }

    public function edit(Attribute $attribute): View
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        $data = $request->validated();
        // Leaving slug blank keeps the existing one — renaming shouldn't silently break references to it.
        $data['slug'] = ($data['slug'] ?? null) ?: $attribute->slug;
        $data['is_filterable'] = $request->boolean('is_filterable');
        $data['options'] = $this->parseOptions($data['options'] ?? null);

        $attribute->update($data);

        return redirect()->route('admin.attributes.index')->with('status', 'Attribute updated.');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        if ($attribute->productValues()->exists()) {
            return back()->withErrors(['attribute' => 'Cannot delete an attribute that is used by products.']);
        }

        $attribute->series()->detach();
        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('status', 'Attribute deleted.');
    }

    /**
     * Convert a comma-separated "options" input into a JSON-storable array.
     */
    private function parseOptions(?string $options): ?array
    {
        if (! $options) {
            return null;
        }

        return array_values(array_filter(array_map('trim', explode(',', $options))));
    }
}
