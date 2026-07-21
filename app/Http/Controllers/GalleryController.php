<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::query()
            ->where('status', true)
            ->with(['images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->map(function (GalleryAlbum $album) {
                $images = $album->images->map(function (GalleryImage $image) {
                    $url = $this->galleryImageUrl($image->image);

                    if (! $url) {
                        return null;
                    }

                    $image->setAttribute('display_url', $url);

                    return $image;
                })->filter()->values();

                $album->setRelation('images', $images);

                return $album;
            })
            ->filter(fn (GalleryAlbum $album) => $album->images->isNotEmpty())
            ->values();

        $images = $albums->flatMap(fn (GalleryAlbum $album) => $album->images);

        return view('gallery', [
            'images' => $images->values(),
            'galleryStats' => [
                'images' => $images->count(),
            ],
        ]);
    }

    private function galleryImageUrl(string $path): ?string
    {
        if (Str::startsWith($path, ['https://', 'http://'])) {
            return $path;
        }

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::url($path);
    }
}
