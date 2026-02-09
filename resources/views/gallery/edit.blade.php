<x-app-layout>
    <x-slot name="header">
    
    </x-slot>

    <div class="py-4">
            <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-pencil me-2"></i>Edit Gallery Image
            </h2>
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Gallery
            </a>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- Current Image Preview -->
                                <div class="mb-4 text-center">
                                    <label class="form-label fw-bold">Current Image</label>
                                    <div class="current-image-container">
                                        <img src="{{ Storage::disk('public')->url($gallery->image_path) }}" 
                                             alt="{{ $gallery->title }}" 
                                             class="img-fluid rounded shadow-sm"
                                             style="max-height: 300px; object-fit: contain;">
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $gallery->image_name }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Image Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="events" {{ old('category', $gallery->category) == 'events' ? 'selected' : '' }}>Events</option>
                                        <option value="programs" {{ old('category', $gallery->category) == 'programs' ? 'selected' : '' }}>Programs</option>
                                        <option value="community" {{ old('category', $gallery->category) == 'community' ? 'selected' : '' }}>Community</option>
                                        <option value="leadership" {{ old('category', $gallery->category) == 'leadership' ? 'selected' : '' }}>Leadership</option>
                                        <option value="general" {{ old('category', $gallery->category) == 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Change Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 5MB</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ $gallery->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (Visible on website)
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('gallery.index') }}" class="btn btn-outline-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Update Image
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>