@if ($errors->any())
    <div class="alert alert-danger"><strong>Please correct these fields:</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-4 admin-blog-editor">
    <div class="col-xl-8">
        <div class="card shadow-sm"><div class="card-header bg-white fw-semibold">Article content</div><div class="card-body">
            <div class="mb-3"><label for="title" class="form-label">Title</label><input id="title" name="title" value="{{ old('title', $post->title) }}" class="form-control form-control-lg @error('title') is-invalid @enderror" required></div>
            <div class="mb-3"><label for="slug" class="form-label">URL slug <small class="text-muted">(generated automatically if blank)</small></label><div class="input-group"><span class="input-group-text">/blog/</span><input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="form-control @error('slug') is-invalid @enderror"></div></div>
            <div class="mb-3"><label for="excerpt" class="form-label">Short summary</label><textarea id="excerpt" name="excerpt" rows="3" maxlength="1000" class="form-control @error('excerpt') is-invalid @enderror" placeholder="A short introduction shown on the blog listing...">{{ old('excerpt', $post->excerpt) }}</textarea></div>
            <div><label for="content" class="form-label">Article content</label><textarea id="content" name="content" rows="18" class="form-control admin-blog-content @error('content') is-invalid @enderror" data-blog-editor data-upload-url="{{ route('admin.blog.images.upload') }}" required>{{ old('content', $post->content) }}</textarea><div class="form-text">Format your article and upload content images directly from the editor toolbar.</div></div>
        </div></div>
        <div class="card shadow-sm mt-4"><div class="card-header bg-white fw-semibold">Search engine details</div><div class="card-body">
            <div class="mb-3"><label for="meta_title" class="form-label">SEO title</label><input id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="255" class="form-control"><div class="form-text">Leave blank to use the article title.</div></div>
            <div><label for="meta_description" class="form-label">SEO description</label><textarea id="meta_description" name="meta_description" rows="3" maxlength="500" class="form-control">{{ old('meta_description', $post->meta_description) }}</textarea></div>
        </div></div>
    </div>
    <div class="col-xl-4">
        <div class="card shadow-sm"><div class="card-header bg-white fw-semibold">Publish settings</div><div class="card-body">
            <div class="mb-3"><label for="blog_category_id" class="form-label">Category</label><select id="blog_category_id" name="blog_category_id" class="form-select"><option value="">Uncategorised</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('blog_category_id', $post->blog_category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="mb-3"><label for="author" class="form-label">Author</label><input id="author" name="author" value="{{ old('author', $post->author ?: 'Rocket Batteries') }}" class="form-control"></div>
            <div class="mb-3"><label for="published_at" class="form-label">Publish date</label><input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="form-control"></div>
            <div class="form-check form-switch"><input type="checkbox" class="form-check-input" id="status" name="status" value="1" @checked(old('status', $post->status ?? true))><label class="form-check-label" for="status">Published and visible</label></div>
        </div></div>
        <div class="card shadow-sm mt-4"><div class="card-header bg-white fw-semibold">Featured image</div><div class="card-body">
            @if ($post->featured_image_url)<img src="{{ $post->featured_image_url }}" alt="" class="admin-blog-preview">@endif
            <input type="file" id="featured_image" name="featured_image" accept=".jpg,.jpeg,.png,.webp" class="form-control"><div class="form-text">JPG, PNG or WebP. Maximum 8MB. A 16:9 image works best.</div>
        </div></div>
        <div class="d-grid gap-2 mt-4"><button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check2-circle me-1"></i> Save Article</button><a href="{{ route('admin.blog.index') }}" class="btn btn-light">Cancel</a></div>
    </div>
</div>

@vite('resources/js/blog-editor.js')
