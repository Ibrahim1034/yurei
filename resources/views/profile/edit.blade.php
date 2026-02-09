<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person me-2"></i>{{ __('My Profile') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>Profile updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>Please fix the errors below.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Profile Picture Section -->
                    <div class="dashboard-card mb-4" style="height: 25%;">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <img src="{{ Auth::user()->profile_picture_url }}" 
                                     alt="Profile Picture" 
                                     class="rounded-circle shadow mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
                                <p class="text-muted small">Member ID: {{ Auth::user()->membership_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold mb-3">
                                    <i class="bi bi-camera me-2"></i>Profile Picture
                                </h5>
                                <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <label for="profile_picture" class="form-label">Update Profile Picture</label>
                                            <input type="file" 
                                                   name="profile_picture" 
                                                   id="profile_picture" 
                                                   class="form-control @error('profile_picture') is-invalid @enderror" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                            @error('profile_picture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF</div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-upload me-1"></i>Update Picture
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Name Update Section -->
                    <div class="dashboard-card mb-4" style="height: 12%;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person-badge me-2"></i>Update Name
                        </h5>
                        <form action="{{ route('profile.name.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', Auth::user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-check-lg me-1"></i>Update Name
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Email Update Section -->
                    <div class="dashboard-card mb-4" style="height: 12%;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-envelope me-2"></i>Update Email Address
                        </h5>
                        <form action="{{ route('profile.email.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-check-lg me-1"></i>Update Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Phone Number Update Section -->
                    <div class="dashboard-card mb-4" style="height: 12%;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-telephone me-2"></i>Update Phone Number
                        </h5>
                        <form action="{{ route('profile.phone.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" 
                                           name="phone_number" 
                                           id="phone_number" 
                                           class="form-control @error('phone_number') is-invalid @enderror" 
                                           value="{{ old('phone_number', Auth::user()->phone_number) }}" 
                                           placeholder="e.g., 254712345678"
                                           required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Format: 254XXXXXXXXX</div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-check-lg me-1"></i>Update Phone
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Update Section -->
                    <div class="dashboard-card mb-4" style="height: 25%;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-shield-lock me-2"></i>Update Password
                        </h5>
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="current_password" 
                                               id="current_password" 
                                               class="form-control @error('current_password') is-invalid @enderror">
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('current_password')" style="border: none; background: none;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="new_password" 
                                               id="new_password" 
                                               class="form-control @error('new_password') is-invalid @enderror">
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('new_password')" style="border: none; background: none;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="new_password_confirmation" 
                                               id="new_password_confirmation" 
                                               class="form-control">
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('new_password_confirmation')" style="border: none; background: none;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end mb-3">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-key me-1"></i>Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Account Information -->
                    <div class="dashboard-card" style="height: 15%;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Account Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted small">MEMBERSHIP NUMBER</strong>
                                <span>{{ Auth::user()->membership_number ?? 'Not assigned' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted small">ACCOUNT STATUS</strong>
                                <span class="badge bg-{{ Auth::user()->is_active ? 'success' : 'warning' }}">
                                    {{ Auth::user()->is_active ? 'Active' : 'Pending Activation' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted small">REGISTRATION DATE</strong>
                                <span>{{ Auth::user()->registration_date ? Auth::user()->registration_date->format('M d, Y') : 'Not set' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="d-block text-muted small">MEMBERSHIP EXPIRES</strong>
                                <span>{{ Auth::user()->expiration_date ? Auth::user()->expiration_date->format('M d, Y') : 'Not set' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentNode.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</x-app-layout>