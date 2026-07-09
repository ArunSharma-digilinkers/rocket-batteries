<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug <span class="text-muted small">(auto-generated if left blank)</span></label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if ($category->image)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image) }}" alt="" class="mt-2" style="max-height: 80px;">
    @endif
</div>

<div class="row">
    <div class="col-sm-6 mb-3">
        <label for="sort_order" class="form-label">Sort Order</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-sm-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $category->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-link">Cancel</a>
