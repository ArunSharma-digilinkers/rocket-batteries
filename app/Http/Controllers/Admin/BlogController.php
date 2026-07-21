<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('admin.blog.index', [
            'posts' => BlogPost::with('category')->latest()->paginate(15),
            'categories' => BlogCategory::withCount('posts')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.create', [
            'post' => new BlogPost(['status' => true, 'published_at' => now()]),
            'categories' => BlogCategory::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if (! $data['slug'] || BlogPost::where('slug', $data['slug'])->exists()) {
            return back()->withInput()->withErrors(['slug' => 'Please enter a unique URL slug for this post.']);
        }
        $data['status'] = $request->boolean('status');
        $data['published_at'] = $data['published_at'] ?: null;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('admin.blog.edit', [
            'post' => $blog,
            'categories' => BlogCategory::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $this->validated($request, $blog);
        $data['slug'] = $data['slug'] ?: $blog->slug;
        $data['status'] = $request->boolean('status');
        $data['published_at'] = $data['published_at'] ?: null;

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post deleted.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
        ]);

        $path = $request->file('upload')->store('blog/content', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:blog_categories,name'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = Str::slug($data['name']);
        if (! $slug || BlogCategory::where('slug', $slug)->exists()) {
            return back()->withInput()->withErrors(['category' => 'Please choose a category name with a unique URL.']);
        }

        BlogCategory::create([
            'name' => $data['name'],
            'slug' => $slug,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Blog category added.');
    }

    public function destroyCategory(BlogCategory $category): RedirectResponse
    {
        if ($category->posts()->exists()) {
            return back()->withErrors(['category' => 'Move or delete posts in this category first.']);
        }
        $category->delete();

        return back()->with('status', 'Blog category deleted.');
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        return $request->validate([
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'author' => ['nullable', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
