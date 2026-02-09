<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4"> 
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}
            </h2>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">Member</span>
                <span class="text-muted">Welcome, {{ Auth::user()->name }}!</span>
            </div>
        </div>
        <div class="container">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <!-- Invitations Card -->
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center my-2">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h5>Invitations</h5>
                        <h3 class="text-primary">{{ Auth::user()->eventRegistrations->whereNotNull('invitation_code')->count() }}</h3>
                        <p class="text-muted small">My Invitations</p>
                        <a href="{{ route('user.invitations') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-eye me-1"></i>View Invitations
                        </a>
                    </div>
                </div>

                <!-- Events Card -->
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h5>Events</h5>
                        <h3 class="text-primary">{{ \App\Models\Event::where('event_date', '>=', now())->count() }}</h3>
                        <p class="text-muted small">Upcoming Events</p>
                        <a href="{{ url('/#events') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-eye me-1"></i>View Events
                        </a>
                    </div>
                </div>

                <!-- Programs Card -->
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <h5>Programs</h5>
                        <h3 class="text-primary">{{ \App\Models\Program::where('start_date', '>=', now())->count() }}</h3>
                        <p class="text-muted small">Available Programs</p>
                        <a href="{{ route('programs.list') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-eye me-1"></i>View Programs
                        </a>
                    </div>
                </div>

                <!-- Membership Card -->
                <div class="col-md-3 my-2">
                    <div class="dashboard-card text-center">
                        <div class="dashboard-icon mx-auto">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5>Membership</h5>
                        @if(Auth::user()->membershipCard)
                            <h3 class="text-success">Active</h3>
                            <p class="text-muted small">Membership Card</p>
                            <div class="d-grid gap-2 mt-2">
                                <a href="{{ route('membership-card.show') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>View Card
                                </a>
                                <a href="{{ route('membership-card.print') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            </div>
                        @else
                            <h3 class="text-warning">Inactive</h3>
                            <p class="text-muted small">No Membership Card</p>
                            <a href="{{ route('membership-card.create') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Create Card
                            </a>
                        @endif
                    </div>
                </div>
            </div>

          

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-8 my-2">
                    <div class="dashboard-card">
                        <h4 class="fw-bold mb-4">
                            <i class="bi bi-clock-history me-2"></i>Recent Activity
                        </h4>
                        <div class="list-group list-group-flush">
                            @php
                                $recentRegistrations = Auth::user()->eventRegistrations->sortByDesc('created_at')->take(3);
                            @endphp
                            
                            @if($recentRegistrations->count() > 0)
                                @foreach($recentRegistrations as $registration)
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="bg-primary rounded p-2 me-3">
                                            <i class="bi bi-calendar-check text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Registered for {{ $registration->event->title }}</h6>
                                            <small class="text-muted">{{ $registration->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-success">Registered</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-secondary rounded p-2 me-3">
                                        <i class="bi bi-info-circle text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">No recent activity</h6>
                                        <small class="text-muted">Your activity will appear here</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-4 my-2">
                    <div class="dashboard-card">
                        <h4 class="fw-bold mb-4">
                            <i class="bi bi-lightning me-2"></i>Quick Actions
                        </h4>
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.invitations') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-envelope me-2"></i>My Invitations
                            </a>
                            <a href="{{ url('/#events') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-calendar-event me-2"></i>View Events
                            </a>
                            <a href="{{ route('programs.list') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-calendar-range me-2"></i>View Programs
                            </a>
                            @if(Auth::user()->membershipCard)
                                <a href="{{ route('membership-card.show') }}" class="btn btn-outline-primary text-start">
                                    <i class="bi bi-person-badge me-2"></i>View Membership Card
                                </a>
                            @else
                                <a href="{{ route('membership-card.create') }}" class="btn btn-outline-primary text-start">
                                    <i class="bi bi-plus-circle me-2"></i>Create Membership Card
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        // Check if we should show program registration success
        if (sessionStorage.getItem('showProgramSuccess') === 'true') {
            showToast('success', 'Program registration completed successfully! Check your email for confirmation.');
            sessionStorage.removeItem('showProgramSuccess');
        }
    });

    function showToast(type, message) {
        // Replace with your existing toast implementation
        const toast = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        $('#toastContainer').append(toast);
        $('.toast').toast('show');
    }
    </script>
    @endpush
</x-app-layout>