<x-app-layout>
    <x-slot name="header">
    
    </x-slot>

    <div class="py-4">
            <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-pencil me-2"></i>Edit User: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Users
            </a>
           </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="dashboard-card">
                        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="text-center mb-4">
                                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" 
                                     class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                                <p class="text-muted">Current Profile Picture</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                               id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="2547XXXXXXXX">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Format: 2547XXXXXXXX (e.g., 254712345678)</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role *</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                            <option value="0" {{ old('role', $user->role) == 0 ? 'selected' : '' }}>Regular User</option>
                                            <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Administrator</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to keep current picture. Accepted formats: JPEG, PNG, JPG. Max size: 2MB</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expiration_date" class="form-label">Expiration Date</label>
                                        <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" 
                                               id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $user->expiration_date?->format('Y-m-d')) }}">
                                        @error('expiration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active User</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>