@extends('admin.layouts.app')

@section('title', 'Gallery Images')

@section('actions')
    <a href="{{ route('gallery') }}" class="btn btn-outline-secondary btn-sm" target="_blank">View Gallery</a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Upload failed:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Upload Gallery Images</div>
        <div class="card-body">
            <form action="{{ route('admin.gallery.images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="gallery-images" class="form-label">Select Images</label>
                <div class="input-group">
                    <input type="file" id="gallery-images" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple required>
                    <button type="submit" class="btn btn-primary">Upload Images</button>
                </div>
                <div class="form-text">Upload up to 20 JPG, PNG, or WebP images at once. Maximum file size: 8MB per image.</div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Uploaded Images</h2>
        <span class="text-muted small">{{ $images->count() }} total</span>
    </div>

    <div class="row g-4">
        @forelse ($images as $image)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm">
                    @if (Storage::disk('public')->exists($image->image))
                        <img src="{{ Storage::url($image->image) }}" class="card-img-top" alt="{{ $image->caption }}" style="height: 210px; object-fit: cover;">
                    @else
                        <div class="d-grid place-items-center bg-light text-muted" style="height: 210px; place-items: center;">
                            <span><i class="bi bi-image me-1"></i> File missing</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{ route('admin.gallery.images.update', $image) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-2">
                                <label for="caption-{{ $image->id }}" class="form-label small">Caption</label>
                                <input type="text" id="caption-{{ $image->id }}" name="caption" value="{{ $image->caption }}" class="form-control form-control-sm" placeholder="Image caption">
                            </div>
                            <div class="mb-3">
                                <label for="sort-{{ $image->id }}" class="form-label small">Sort Order</label>
                                <input type="number" id="sort-{{ $image->id }}" name="sort_order" value="{{ $image->sort_order }}" min="0" class="form-control form-control-sm">
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Save Changes</button>
                        </form>
                        <form action="{{ route('admin.gallery.images.destroy', $image) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this gallery image?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete Image</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted bg-light border rounded py-5">
                    <i class="bi bi-images fs-2 d-block mb-2"></i>
                    No gallery images uploaded yet.
                </div>
            </div>
        @endforelse
    </div>
@endsection
