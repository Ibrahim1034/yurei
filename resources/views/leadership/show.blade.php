<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person me-2"></i>Leader Details: {{ $leader->name }}
            </h2>
            <a href="{{ route('leadership.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Leaders
            </a>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!-- Leader Profile Card -->
                    <div class="dashboard-card text-center">
                        <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" 
                             class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                        <h4>{{ $leader->name }}</h4>
                        <p class="text-muted">{{ $leader->position }}</p>
                        
                        <div class="mb-3">
                            <span class="badge {{ $leader->is_active ? 'bg-success' : 'bg-warning' }}">
                                {{ $leader->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('leadership.edit', $leader) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit Leader
                            </a>
                        </div>

                        <!-- Social Links -->
                        @if($leader->social_facebook || $leader->social_twitter || $leader->social_linkedin || $leader->social_instagram)
                            <hr>
                            <h6 class="fw-bold mb-3">Social Links</h6>
                            <div class="d-flex justify-content-center gap-2">
                                @if($leader->social_facebook)
                                    <a href="{{ $leader->social_facebook }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                @endif
                                @if($leader->social_twitter)
                                    <a href="{{ $leader->social_twitter }}" target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                @endif
                                @if($leader->social_linkedin)
                                    <a href="{{ $leader->social_linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                @endif
                                @if($leader->social_instagram)
                                    <a href="{{ $leader->social_instagram }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Leader Details -->
                    <div class="dashboard-card">
                        <h5 class="fw-bold mb-4">Leader Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <p class="form-control-plaintext">{{ $leader->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Position</label>
                                    <p class="form-control-plaintext">{{ $leader->position }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <p class="form-control-plaintext">{{ $leader->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <p class="form-control-plaintext">{{ $leader->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Bio/Description</label>
                                    <p class="form-control-plaintext">{{ $leader->bio ?? 'No bio available.' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Display Order</label>
                                    <p class="form-control-plaintext">{{ $leader->order }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $leader->is_active ? 'bg-success' : 'bg-warning' }}">
                                            {{ $leader->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Last Updated</label>
                                    <p class="form-control-plaintext">{{ $leader->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>