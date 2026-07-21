<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_lists_published_posts_and_hides_drafts(): void
    {
        $published = BlogPost::create([
            'title' => 'How to extend battery life',
            'slug' => 'extend-battery-life',
            'content' => 'Useful battery care advice.',
            'status' => true,
            'published_at' => now(),
        ]);

        BlogPost::create([
            'title' => 'Private draft article',
            'slug' => 'private-draft',
            'content' => 'Not ready.',
            'status' => false,
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee('Private draft article');

        $this->get(route('blog.show', $published))
            ->assertOk()
            ->assertSee('Useful battery care advice.');
    }

    public function test_draft_and_future_posts_cannot_be_opened_publicly(): void
    {
        $draft = BlogPost::create(['title' => 'Draft', 'slug' => 'draft', 'content' => 'Draft', 'status' => false]);
        $future = BlogPost::create(['title' => 'Future', 'slug' => 'future', 'content' => 'Future', 'status' => true, 'published_at' => now()->addDay()]);

        $this->get(route('blog.show', $draft))->assertNotFound();
        $this->get(route('blog.show', $future))->assertNotFound();
    }

    public function test_admin_can_create_a_blog_post_with_an_image(): void
    {
        Storage::fake('public');
        $admin = AdminUser::factory()->create();
        $category = BlogCategory::create(['name' => 'Battery Care', 'slug' => 'battery-care']);

        $this->actingAs($admin, 'admin')->post(route('admin.blog.store'), [
            'blog_category_id' => $category->id,
            'title' => 'Choosing the right backup battery',
            'slug' => '',
            'excerpt' => 'A practical buying guide.',
            'content' => 'Start with your required load and backup duration.',
            'author' => 'Rocket Batteries',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'status' => '1',
            'featured_image' => UploadedFile::fake()->image('battery-guide.jpg', 1200, 675),
        ])->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::firstOrFail();
        $this->assertSame('choosing-the-right-backup-battery', $post->slug);
        $this->assertTrue($post->status);
        Storage::disk('public')->assertExists($post->featured_image);
    }

    public function test_guest_cannot_access_blog_admin(): void
    {
        $this->get(route('admin.blog.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_upload_an_editor_image(): void
    {
        Storage::fake('public');
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.blog.images.upload'), [
            'upload' => UploadedFile::fake()->image('article-image.jpg', 1000, 700),
        ]);

        $response->assertOk()->assertJsonStructure(['url']);
        $path = str_replace('/storage/', '', $response->json('url'));
        Storage::disk('public')->assertExists($path);
    }
}
