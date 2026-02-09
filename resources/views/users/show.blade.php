<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person me-2"></i>User Details: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Users
            </a>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!-- User Profile Card -->
                    <div class="dashboard-card text-center">
                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" 
                             class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-info' }}">
                                {{ $user->isAdmin() ? 'Administrator' : 'Regular User' }}
                            </span>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-warning' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit User
                            </a>
                            @if($user->membershipCard)
                                <a href="{{ route('users.membership-card', $user) }}" class="btn btn-info text-white">
                                    <i class="bi bi-person-badge me-1"></i>View Membership Card
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- User Details -->
                    <div class="dashboard-card">
                        <h5 class="fw-bold mb-4">User Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <p class="form-control-plaintext">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <p class="form-control-plaintext">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <p class="form-control-plaintext">{{ $user->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Membership Number</label>
                                    <p class="form-control-plaintext">
                                        @if($user->membership_number)
                                            <span class="badge bg-primary">{{ $user->membership_number }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Registration Date</label>
                                    <p class="form-control-plaintext">{{ $user->registration_date?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Expiration Date</label>
                                    <p class="form-control-plaintext {{ $user->expiration_date?->isPast() ? 'text-danger' : '' }}">
                                        {{ $user->expiration_date?->format('M d, Y') ?? 'N/A' }}
                                        @if($user->expiration_date?->isPast())
                                            <span class="badge bg-danger ms-1">Expired</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Membership Card Information -->
                        @if($user->membershipCard)
                            <hr>
                            <h6 class="fw-bold mb-3">Membership Card</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Card Status</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $user->membershipCard->is_active ? 'bg-success' : 'bg-warning' }}">
                                                {{ $user->membershipCard->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Card Expiration</label>
                                        <p class="form-control-plaintext {{ $user->membershipCard->is_expired ? 'text-danger' : '' }}">
                                            {{ $user->membershipCard->expiration_date->format('M d, Y') }}
                                            @if($user->membershipCard->is_expired)
                                                <span class="badge bg-danger ms-1">Expired</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>