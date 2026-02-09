<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
            </h2>
            <div class="d-flex gap-2">
                <span class="badge bg-danger">Administrator</span>
                <span class="text-muted">Welcome, {{ Auth::user()->name }}!</span>
            </div>
        </div>
        
        <div class="container">
            <!-- Admin Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5>Total Users</h5>
                        <h3 class="text-primary">{{ \App\Models\User::count() }}</h3>
                        <p class="text-muted small">Registered Users</p>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5>Membership Cards</h5>
                        <h3 class="text-primary">{{ \App\Models\MembershipCard::count() }}</h3>
                        <p class="text-muted small">Active Cards</p>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h5>Events</h5>
                        <h3 class="text-primary">{{ \App\Models\Event::count() }}</h3>
                        <p class="text-muted small">Total Events</p>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h5>Revenue</h5>
                        <h3 class="text-primary">KSH {{ number_format($totalRevenue ?? 0) }}</h3>
                        <p class="text-muted small">Total Revenue</p>
                    </div>
                </div>
            </div>

            <!-- Admin Management Cards -->
            <div class="row">
                <!-- Users Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-primary mb-3">
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Users Management</h4>
                            <p class="text-muted mb-4">Manage all registered users, their profiles and permissions</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-primary">
                                    <i class="bi bi-eye me-1"></i>View Users
                                </a>
                                <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Add User
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leadership Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-success mb-3">
                                <i class="bi bi-person-badge text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Leadership</h4>
                            <p class="text-muted mb-4">Manage organization leaders, their profiles and positions</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('leadership.index') }}" class="btn btn-success">
                                    <i class="bi bi-eye me-1"></i>View Leaders
                                </a>
                                <a href="{{ route('leadership.create') }}" class="btn btn-outline-success">
                                    <i class="bi bi-plus-circle me-1"></i>Add Leader
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-info mb-3">
                                <i class="bi bi-calendar-event text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Events Management</h4>
                            <p class="text-muted mb-4">Create and manage events, registrations and schedules</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('events.index') }}" class="btn btn-info text-white">
                                    <i class="bi bi-eye me-1"></i>View Events
                                </a>
                                <a href="{{ route('events.create') }}" class="btn btn-outline-info">
                                    <i class="bi bi-plus-circle me-1"></i>Create Event
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-info mb-3">
                                <i class="bi bi-images text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Gallery Management</h4>
                            <p class="text-muted mb-4">Manage gallery images, categories and visibility</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('gallery.index') }}" class="btn btn-info text-white">
                                    <i class="bi bi-eye me-1"></i>View Gallery
                                </a>
                                <a href="{{ route('gallery.create') }}" class="btn btn-outline-info">
                                    <i class="bi bi-plus-circle me-1"></i>Add Image
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invitations Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-warning mb-3">
                                <i class="bi bi-envelope text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Invitations</h4>
                            <p class="text-muted mb-4">Assign and manage invitation cards for users and events</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('invitations.index') }}" class="btn btn-warning text-white">
                                    <i class="bi bi-eye me-1"></i>View Invitations
                                </a>
                                <a href="{{ route('invitations.create') }}" class="btn btn-outline-warning">
                                    <i class="bi bi-send me-1"></i>Assign Invitations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programs Management -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-info mb-3">
                                <i class="bi bi-calendar-range text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Programs Management</h4>
                            <p class="text-muted mb-4">Create and manage multi-day programs and registrations</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('programs.index') }}" class="btn btn-info text-white">
                                    <i class="bi bi-eye me-1"></i>View Programs
                                </a>
                                <a href="{{ route('programs.create') }}" class="btn btn-outline-info">
                                    <i class="bi bi-plus-circle me-1"></i>Create Program
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Resources -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-secondary mb-3">
                                <i class="bi bi-folder text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Important Resources</h4>
                            <p class="text-muted mb-4">Manage official documents and resources for users</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-files me-1"></i>View Resources
                                </a>
                                <a href="{{ route('documents.create') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-upload me-1"></i>Upload Documents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments & Donations -->
                <div class="col-md-4 mb-4">
                    <div class="dashboard-card h-100">
                        <div class="text-center">
                            <div class="dashboard-icon mx-auto bg-danger mb-3">
                                <i class="bi bi-currency-dollar text-white"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Payments & Donations</h4>
                            <p class="text-muted mb-4">View transactions, donations and payment information</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.donations.index') }}" class="btn btn-danger">
                                    <i class="bi bi-receipt me-1"></i>View Transactions
                                </a>
                                <a href="{{ route('admin.donations.financial-report') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-graph-up me-1"></i>Financial Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>