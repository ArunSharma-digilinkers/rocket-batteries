<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'images' => GalleryImage::with('album')->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function storeImages(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $album = GalleryAlbum::firstOrCreate(
            ['slug' => 'gallery'],
            [
                'title' => 'Photo Gallery',
                'description' => 'Rocket Batteries photo gallery.',
                'sort_order' => 0,
                'status' => true,
            ]
        );

        if (! $album->status) {
            $album->update(['status' => true]);
        }

        $sortOrder = (int) GalleryImage::max('sort_order');

        foreach ($request->file('images') as $file) {
            $path = $file->store('gallery', 'public');
            $image = $album->images()->create([
                'image' => $path,
                'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'sort_order' => ++$sortOrder,
            ]);

            if (! $album->cover_image) {
                $album->update(['cover_image' => $image->image]);
            }
        }

        return back()->with('status', count($request->file('images')).' gallery image(s) uploaded.');
    }

    public function updateImage(Request $request, GalleryImage $image): RedirectResponse
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $image->update([
            'caption' => $data['caption'] ?: null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Gallery image updated.');
    }

    public function destroyImage(GalleryImage $image): RedirectResponse
    {
        $album = $image->album;
        Storage::disk('public')->delete($image->image);
        $wasCover = $album && $album->cover_image === $image->image;
        $image->delete();

        if ($wasCover) {
            $album->update(['cover_image' => $album->images()->orderBy('sort_order')->value('image')]);
        }

        return back()->with('status', 'Gallery image deleted.');
    }
}
