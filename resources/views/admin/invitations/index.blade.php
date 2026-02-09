
<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 10%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-envelope me-2"></i>Invitations Management
            </h2>
            <div>
                <a href="{{ route('invitations.create') }}" class="btn btn-primary my-2">
                    <i class="bi bi-send me-1"></i>Assign Invitations
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
        
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="dashboard-card">
                <h4 class="fw-bold mb-4">
                    <i class="bi bi-calendar-event me-2"></i>Events with Invitations
                </h4>

                @if($events->count() > 0)
                    <div class="row">
                        @foreach($events as $event)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    @if($event->image)
                                        <img src="{{ Storage::disk('public')->url($event->image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $event->title }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="bi bi-calendar-event text-muted display-4"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $event->title }}</h5>
                                        <p class="card-text small text-muted">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $event->event_date->format('M d, Y h:i A') }}
                                        </p>
                                        <p class="card-text small text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $event->venue }}
                                        </p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-people me-1"></i>
                                                    {{ $event->registrations->whereNotNull('invitation_code')->count() }} invitations
                                                </small>
                                                @if($event->is_paid)
                                                    <span class="badge bg-warning">KES {{ number_format($event->registration_fee, 2) }}</span>
                                                @else
                                                    <span class="badge bg-success">Free</span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('invitations.event.registrations', $event) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>View Attendees
                                                </a>
                                                
                                                @if($event->event_date->isToday() || $event->event_date->isPast())
                                                <a href="{{ route('invitations.export', $event) }}" 
                                                   class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-download me-1"></i>Export Report
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-envelope display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No invitations sent yet</h5>
                        <p class="text-muted">Start by assigning invitations to users for your events.</p>
                        <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Assign Invitations
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>