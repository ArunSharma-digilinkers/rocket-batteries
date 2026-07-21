@extends('admin.layouts.app')

@section('title', 'Blog')
@section('actions')
    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i> View Blog</a>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> New Post</a>
@endsection

@section('content')
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card shadow-sm admin-blog-list">
                <div class="card-header bg-white d-flex justify-content-between align-items-center"><strong>All Posts</strong><span class="text-muted small">{{ $posts->total() }} total</span></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Article</th><th>Category</th><th>Published</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td><div class="admin-blog-title">
                                        @if ($post->featured_image_url)<img src="{{ $post->featured_image_url }}" alt="">@else<span><i class="bi bi-file-earmark-text"></i></span>@endif
                                        <div><strong>{{ $post->title }}</strong><small>/blog/{{ $post->slug }}</small></div>
                                    </div></td>
                                    <td>{{ $post->category?->name ?: 'Uncategorised' }}</td>
                                    <td>{{ $post->published_at?->format('d M Y') ?: 'Not set' }}</td>
                                    <td><span class="badge {{ $post->status ? 'bg-success' : 'bg-secondary' }}">{{ $post->status ? 'Published' : 'Draft' }}</span></td>
                                    <td class="text-end text-nowrap">
                                        @if ($post->status)<a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>@endif
                                        <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this blog post?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button></form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5"><div class="admin-blog-empty"><i class="bi bi-journal-richtext"></i><strong>No blog posts yet</strong><span>Create your first article to start the blog.</span><a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">Create first post</a></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($posts->hasPages())<div class="card-footer bg-white">{{ $posts->links() }}</div>@endif
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Categories</div>
                <div class="card-body">
                    <form action="{{ route('admin.blog.categories.store') }}" method="POST" class="mb-4">@csrf
                        <label for="category-name" class="form-label">New category</label>
                        <div class="input-group"><input id="category-name" name="name" class="form-control" placeholder="e.g. Battery Care" required><button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg"></i></button></div>
                    </form>
                    <div class="admin-blog-categories">
                        @forelse ($categories as $category)
                            <div><span><strong>{{ $category->name }}</strong><small>{{ $category->posts_count }} posts</small></span>
                                <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">@csrf @method('DELETE')<button type="submit" aria-label="Delete {{ $category->name }}"><i class="bi bi-trash"></i></button></form>
                            </div>
                        @empty <p class="text-muted small mb-0">No categories yet.</p> @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
