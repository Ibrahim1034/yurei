<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person-badge me-2"></i>Create Membership Card
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="dashboard-card">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle me-2"></i>Photo Requirements</h6>
                            <ul class="mb-0 small">
                                <li>Square photo (equal width and height)</li>
                                <li>Minimum 300x300 pixels</li>
                                <li>Clear, recent passport-style photo</li>
                                <li>Face should be clearly visible</li>
                                <li>File size should not exceed 2MB</li>
                                <li>Accepted formats: JPEG, PNG, JPG</li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('membership-card.store') }}" enctype="multipart/form-data" id="cardForm">
                            @csrf

                            <!-- Hidden input for cropped image data -->
                            <input type="hidden" name="cropped_image" id="croppedImage">

                            <div class="mb-4">
                                <label for="card_photo" class="form-label fw-bold">Passport Photo *</label>
                                <input type="file" 
                                       class="form-control form-control-lg @error('card_photo') is-invalid @enderror" 
                                       id="card_photo" 
                                       name="card_photo" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       required>
                                @error('card_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload a photo. You'll be able to crop it to square format.</div>
                            </div>

                            <!-- Image Preview and Cropper -->
                            <div class="mb-4 d-none" id="cropperSection">
                                <label class="form-label fw-bold">Crop Your Photo</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div id="imagePreview" style="max-height: 400px; overflow: hidden;">
                                                    <img id="previewImage" class="img-fluid" alt="Preview">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-grid gap-2 mb-3">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" id="cropBtn">
                                                        <i class="bi bi-scissors me-1"></i>Crop Image
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="resetCrop">
                                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Crop
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" id="cancelCrop">
                                                        <i class="bi bi-x-circle me-1"></i>Cancel & Upload New
                                                    </button>
                                                </div>
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Crop Guide</h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-lightbulb"></i> Drag to position your face in the center of the square.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cropped Image Preview -->
                            <div class="mb-4 d-none" id="croppedPreviewSection">
                                <label class="form-label fw-bold">Final Photo Preview</label>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img id="croppedPreview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;" alt="Cropped Preview">
                                        <div class="mt-2">
                                            <small class="text-muted">This is how your photo will appear on the membership card.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Member Information</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Name:</strong> {{ Auth::user()->name }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Email:</strong> {{ Auth::user()->email }}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <strong>Phone:</strong> {{ Auth::user()->phone_number ?? 'Not provided' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Member Type:</strong> 
                                                <span class="badge {{ Auth::user()->user_type === 'friend' ? 'bg-info' : 'bg-primary' }}">
                                                    {{ Auth::user()->user_type_display }}
                                                </span>
                                            </div>
                                        </div>
                                        @if(Auth::user()->user_type === 'member')
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <strong>County:</strong> {{ Auth::user()->county ?? 'Not provided' }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Constituency:</strong> {{ Auth::user()->constituency ?? 'Not provided' }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Ward:</strong> {{ Auth::user()->ward ?? 'Not provided' }}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <strong>Institution:</strong> {{ Auth::user()->institution ?? 'Not provided' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Status:</strong> 
                                                @if(Auth::user()->graduation_status)
                                                    {{ Auth::user()->graduation_status === 'studying' ? 'Currently Studying' : 'Graduated' }}
                                                @else
                                                    Not provided
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <strong>Member Since:</strong> {{ Auth::user()->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="bi bi-person-badge me-1"></i>Generate Membership Card
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Cropper.js CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-control-lg {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control-lg:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        /* Cropper.js custom styles */
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
        
        .cropper-view-box {
            outline: 2px solid #007bff;
            outline-color: rgba(0, 123, 255, 0.75);
        }
        
        .cropper-line {
            background-color: #007bff;
        }
        
        .cropper-point {
            background-color: #007bff;
            width: 8px;
            height: 8px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('card_photo');
            const cropperSection = document.getElementById('cropperSection');
            const croppedPreviewSection = document.getElementById('croppedPreviewSection');
            const previewImage = document.getElementById('previewImage');
            const croppedPreview = document.getElementById('croppedPreview');
            const croppedImageInput = document.getElementById('croppedImage');
            const cropBtn = document.getElementById('cropBtn');
            const resetCropBtn = document.getElementById('resetCrop');
            const cancelCropBtn = document.getElementById('cancelCrop');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('cardForm');
            
            let cropper;

            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                
                if (files && files.length > 0) {
                    const file = files[0];
                    
                    // Validate file type
                    if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                        alert('Please select a JPEG, PNG, or JPG image.');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB.');
                        fileInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        
                        // Show cropper section
                        cropperSection.classList.remove('d-none');
                        croppedPreviewSection.classList.add('d-none');
                        submitBtn.disabled = true;
                        
                        // Initialize cropper
                        if (cropper) {
                            cropper.destroy();
                        }
                        
                        cropper = new Cropper(previewImage, {
                            aspectRatio: 1, // Square aspect ratio
                            viewMode: 1,
                            autoCropArea: 0.8,
                            movable: true,
                            zoomable: true,
                            rotatable: false,
                            scalable: false,
                            guides: true,
                            highlight: false,
                            background: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            minCropBoxWidth: 100,
                            minCropBoxHeight: 100,
                        });
                    };
                    
                    reader.readAsDataURL(file);
                }
            });

            // Crop button handler
            cropBtn.addEventListener('click', function() {
                if (cropper) {
                    // Get cropped canvas
                    const canvas = cropper.getCroppedCanvas({
                        width: 400,
                        height: 400,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });
                    
                    // Convert canvas to data URL
                    const croppedDataURL = canvas.toDataURL('image/jpeg', 0.9);
                    
                    // Set cropped image preview
                    croppedPreview.src = croppedDataURL;
                    
                    // Set hidden input value
                    croppedImageInput.value = croppedDataURL;
                    
                    // Show cropped preview section
                    croppedPreviewSection.classList.remove('d-none');
                    cropperSection.classList.add('d-none');
                    
                    // Enable submit button
                    submitBtn.disabled = false;
                }
            });

            // Reset crop button handler
            resetCropBtn.addEventListener('click', function() {
                if (cropper) {
                    cropper.reset();
                }
            });

            // Cancel crop button handler
            cancelCropBtn.addEventListener('click', function() {
                // Reset everything
                fileInput.value = '';
                cropperSection.classList.add('d-none');
                croppedPreviewSection.classList.add('d-none');
                submitBtn.disabled = true;
                croppedImageInput.value = '';
                
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Form submission handler
            form.addEventListener('submit', function(e) {
                if (!croppedImageInput.value) {
                    e.preventDefault();
                    alert('Please crop your image before submitting.');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Generating Card...';
            });
        });
    </script>
</x-app-layout>