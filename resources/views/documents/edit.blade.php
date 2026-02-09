<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-pencil me-2"></i>Edit Document
            </h2>
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Documents
            </a>
        </div>
        
        <div class="container">
            <div class="dashboard-card">
                <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Document Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $document->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Registration" {{ old('category', $document->category) == 'Registration' ? 'selected' : '' }}>Registration</option>
                                    <option value="Policy" {{ old('category', $document->category) == 'Policy' ? 'selected' : '' }}>Policy</option>
                                    <option value="Report" {{ old('category', $document->category) == 'Report' ? 'selected' : '' }}>Report</option>
                                    <option value="Guideline" {{ old('category', $document->category) == 'Guideline' ? 'selected' : '' }}>Guideline</option>
                                    <option value="Form" {{ old('category', $document->category) == 'Form' ? 'selected' : '' }}>Form</option>
                                    <option value="Other" {{ old('category', $document->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_file" class="form-label">Document File</label>
                                <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                       id="document_file" name="document_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                <div class="form-text">
                                    Current file: <a href="{{ $document->file_url }}" target="_blank">{{ $document->file_name }}</a> ({{ $document->formatted_file_size }})
                                    <br>Leave empty to keep current file.
                                </div>
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="preview_image" class="form-label">Preview Image</label>
                                <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                                       id="preview_image" name="preview_image" accept="image/*">
                                <div class="form-text">
                                    @if($document->image_url)
                                        Current image: 
                                        <img src="{{ $document->image_url }}" alt="Preview" class="img-thumbnail mt-2" style="max-width: 100px;">
                                        <br>
                                    @endif
                                    Leave empty to keep current image.
                                </div>
                                @error('preview_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Document
                        </button>
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>