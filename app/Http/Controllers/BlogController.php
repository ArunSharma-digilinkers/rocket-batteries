<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $category = null;
        $posts = BlogPost::published()->with('category')->latest('published_at')->latest('id');

        if ($request->filled('category')) {
            $category = BlogCategory::where('slug', $request->string('category'))->first();
            if ($category) {
                $posts->whereBelongsTo($category, 'category');
            }
        }

        return view('blog.index', [
            'posts' => $posts->paginate(9)->withQueryString(),
            'categories' => BlogCategory::whereHas('posts', fn ($query) => $query->published())
                ->withCount(['posts' => fn ($query) => $query->published()])
                ->orderBy('sort_order')->orderBy('name')->get(),
            'activeCategory' => $category,
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->status && (! $post->published_at || $post->published_at->isPast()), 404);
        $post->load('category');

        $relatedPosts = BlogPost::published()
            ->with('category')
            ->whereKeyNot($post->getKey())
            ->when($post->blog_category_id, fn ($query) => $query->where('blog_category_id', $post->blog_category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
