<x-app-layout>
    <x-slot name="header">
      
    </x-slot>


    <div class="py-4">

      <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-images me-2"></i>Gallery Management
            </h2>
            <a href="{{ route('gallery.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add Image
            </a>
        </div>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                @foreach($gallery as $image)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ Storage::disk('public')->url($image->image_path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $image->title }}"
                                 style="height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $image->title }}</h5>
                                <p class="card-text text-muted small">{{ $image->description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">{{ $image->category }}</span>
                                    <span class="badge bg-{{ $image->is_active ? 'success' : 'danger' }}">
                                        {{ $image->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('gallery.edit', $image) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('gallery.toggle-status', $image) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $image->is_active ? 'warning' : 'success' }}">
                                            <i class="bi bi-{{ $image->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('gallery.destroy', $image) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this image?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($gallery->isEmpty())
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No images in gallery yet. 
                            <a href="{{ route('gallery.create') }}" class="alert-link">Add the first image</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>