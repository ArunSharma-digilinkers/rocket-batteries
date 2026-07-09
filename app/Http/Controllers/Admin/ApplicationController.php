<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApplicationRequest;
use App\Http\Requests\Admin\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        return view('admin.applications.index', [
            'applications' => Application::withCount('products')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.applications.create', ['application' => new Application()]);
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        Application::create($data);

        return redirect()->route('admin.applications.index')->with('status', 'Application created.');
    }

    public function edit(Application $application): View
    {
        return view('admin.applications.edit', compact('application'));
    }

    public function update(UpdateApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        $application->update($data);

        return redirect()->route('admin.applications.index')->with('status', 'Application updated.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->products()->detach();
        $application->delete();

        return redirect()->route('admin.applications.index')->with('status', 'Application deleted.');
    }
}
