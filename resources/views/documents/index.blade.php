<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>Documents Management
            </h2>
            <a href="{{ route('documents.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Upload Document
            </a>
        </div>
        
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Quick Action Button -->
            <div class="text-end mb-3">
                <a href="{{ route('documents.create') }}" class="btn btn-success">
                    <i class="bi bi-upload me-1"></i>Upload New Document
                </a>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>File Info</th>
                                <th>Uploaded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                                <tr>
                                    <td>
                                        @if($document->image_url)
                                            <img src="{{ $document->image_url }}" 
                                                 alt="{{ $document->title }}" 
                                                 class="rounded"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-file-earmark-text text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $document->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $document->category }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-filetype-{{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }} me-1"></i>
                                           {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}
                                            <br>
                                            <i class="bi bi-hdd me-1"></i>
                                            {{ $document->formatted_file_size }}
                                        </small>
                                    </td>
                                    <td>{{ $document->user->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ $document->file_url }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('documents.download', $document) }}" 
                                               class="btn btn-outline-success btn-sm"
                                               title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="{{ route('documents.edit', $document) }}" 
                                               class="btn btn-outline-warning btn-sm"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-file-earmark-x display-4 text-muted"></i>
                                        <h5 class="mt-3 text-muted">No documents found</h5>
                                        <p class="text-muted">Get started by uploading your first document.</p>
                                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                                            <i class="bi bi-upload me-1"></i>Upload Document
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>