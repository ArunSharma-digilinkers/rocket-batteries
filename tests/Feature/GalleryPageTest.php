<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_displays_active_album_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/factory.jpg', 'image-content');

        $album = GalleryAlbum::create([
            'title' => 'Factory Tour',
            'slug' => 'factory-tour',
            'description' => 'Inside our facility.',
            'status' => true,
        ]);

        GalleryImage::create([
            'gallery_album_id' => $album->id,
            'image' => 'gallery/factory.jpg',
            'caption' => 'Production line',
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Production line')
            ->assertSee(Storage::url('gallery/factory.jpg'), false);
    }

    public function test_gallery_page_hides_inactive_and_missing_media(): void
    {
        Storage::fake('public');

        $hiddenAlbum = GalleryAlbum::create([
            'title' => 'Private Event',
            'slug' => 'private-event',
            'status' => false,
        ]);

        GalleryImage::create([
            'gallery_album_id' => $hiddenAlbum->id,
            'image' => 'gallery/private.jpg',
            'caption' => 'Hidden photograph',
        ]);

        $missingAlbum = GalleryAlbum::create([
            'title' => 'Missing Files',
            'slug' => 'missing-files',
            'status' => true,
        ]);

        GalleryImage::create([
            'gallery_album_id' => $missingAlbum->id,
            'image' => 'gallery/not-uploaded.jpg',
            'caption' => 'Missing photograph',
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertDontSee('Private Event')
            ->assertDontSee('Missing Files')
            ->assertSee('No gallery images yet');
    }

    public function test_admin_can_upload_gallery_images(): void
    {
        Storage::fake('public');
        $admin = AdminUser::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.gallery.images.store'), [
                'images' => [UploadedFile::fake()->image('event-photo.jpg', 900, 600)],
            ])
            ->assertRedirect();

        $album = GalleryAlbum::where('slug', 'gallery')->firstOrFail();
        $image = GalleryImage::firstOrFail();
        Storage::disk('public')->assertExists($image->image);
        $this->assertSame('event-photo', $image->caption);
        $this->assertSame($image->image, $album->fresh()->cover_image);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('event-photo');
    }

    public function test_guest_cannot_manage_gallery(): void
    {
        $this->get(route('admin.gallery.index'))->assertRedirect(route('admin.login'));
    }
}
