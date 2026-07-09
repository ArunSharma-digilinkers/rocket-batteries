<div class="row">
    <div class="col-sm-6 mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $attribute->name) }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-sm-6 mb-3">
        <label for="slug" class="form-label">Slug <span class="text-muted small">(auto-generated if left blank)</span></label>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $attribute->slug) }}">
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="unit" class="form-label">Unit</label>
        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $attribute->unit) }}" placeholder="e.g. Ah, V, °C">
        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-sm-4 mb-3">
        <label for="group" class="form-label">Group</label>
        <input type="text" class="form-control @error('group') is-invalid @enderror" id="group" name="group" value="{{ old('group', $attribute->group) }}" placeholder="e.g. Electrical">
        @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-sm-4 mb-3">
        <label for="data_type" class="form-label">Data Type</label>
        <select class="form-select @error('data_type') is-invalid @enderror" id="data_type" name="data_type" required>
            @foreach (['text' => 'Text', 'number' => 'Number', 'select' => 'Select'] as $value => $label)
                <option value="{{ $value }}" {{ old('data_type', $attribute->data_type ?? 'text') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('data_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label for="options" class="form-label">Options <span class="text-muted small">(comma-separated, only used when Data Type is "Select")</span></label>
    <input type="text" class="form-control @error('options') is-invalid @enderror" id="options" name="options" value="{{ old('options', is_array($attribute->options) ? implode(', ', $attribute->options) : '') }}">
    @error('options') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-sm-6 mb-3">
        <label for="sort_order" class="form-label">Sort Order</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $attribute->sort_order ?? 0) }}">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-sm-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1" {{ old('is_filterable', $attribute->is_filterable ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_filterable">Filterable on catalog</label>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.attributes.index') }}" class="btn btn-link">Cancel</a>
