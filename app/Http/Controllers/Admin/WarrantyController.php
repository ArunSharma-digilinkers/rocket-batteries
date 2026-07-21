<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Warranty::with('product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return view('admin.warranties.index', [
            'warranties' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['status']),
        ]);
    }

    public function show(Warranty $warranty): View
    {
        $warranty->load(['product', 'verifier']);

        return view('admin.warranties.show', compact('warranty'));
    }

    public function update(Request $request, Warranty $warranty): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,verified,rejected'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $data = $validated;

        if ($validated['status'] === 'verified' && $warranty->status !== 'verified') {
            $data['verified_at'] = now();
            $data['verified_by'] = Auth::guard('admin')->id();
        }

        $warranty->update($data);

        return back()->with('status', 'Warranty updated.');
    }

    public function destroy(Warranty $warranty): RedirectResponse
    {
        $warranty->delete();

        return redirect()->route('admin.warranties.index')->with('status', 'Warranty deleted.');
    }
}
