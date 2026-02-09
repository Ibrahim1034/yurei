<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-calendar-range me-2"></i>Program Details
            </h2>
            <div>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-1"></i>Back to Programs
                </a>
                <a href="{{ route('program.registration.form', $program) }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>Register
                </a>
            </div>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <div class="row">
                            <div class="col-md-4">
                                @if($program->image)
                                    <img src="{{ Storage::disk('public')->url($program->image) }}" 
                                         alt="{{ $program->title }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="bi bi-calendar-range text-muted display-4"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h3 class="fw-bold">{{ $program->title }}</h3>
                                
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <strong><i class="bi bi-calendar me-2"></i>Duration:</strong>
                                        <p class="mb-1">{{ $program->duration ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-6">
                                        <strong><i class="bi bi-geo-alt me-2"></i>Venue:</strong>
                                        <p class="mb-1">{{ $program->venue }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <strong><i class="bi bi-play-circle me-2"></i>Start Date:</strong>
                                        <p class="mb-1">{{ $program->start_date->format('F j, Y \a\t h:i A') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <strong><i class="bi bi-flag me-2"></i>End Date:</strong>
                                        <p class="mb-1">{{ $program->end_date->format('F j, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <strong><i class="bi bi-people me-2"></i>Participants:</strong>
                                        <p class="mb-1">
                                            {{ $program->current_participants }} / 
                                            {{ $program->max_participants ?: 'Unlimited' }}
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <strong><i class="bi bi-tag me-2"></i>Type:</strong>
                                        <p class="mb-1">
                                            @if($program->is_paid)
                                                <span class="badge bg-warning">Paid - KES {{ number_format($program->registration_fee, 2) }}</span>
                                            @else
                                                <span class="badge bg-success">Free</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if($program->registration_deadline)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong><i class="bi bi-clock me-2"></i>Registration Deadline:</strong>
                                        <p class="mb-1">{{ $program->registration_deadline->format('F j, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            @if($program->isRegistrationOpen())
                                                <span class="badge bg-success">Registration Open</span>
                                            @else
                                                <span class="badge bg-danger">Registration Closed</span>
                                            @endif
                                            
                                            @if($program->isUpcoming())
                                                <span class="badge bg-info">Upcoming</span>
                                            @elseif($program->isOngoing())
                                                <span class="badge bg-success">Ongoing</span>
                                            @elseif($program->isCompleted())
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="mt-3">
                            <h5>Program Description</h5>
                            <p class="text-muted">{{ $program->description }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="dashboard-card">
                        <h5 class="fw-bold mb-3">Registration</h5>
                        
                        @if($program->isRegistrationOpen())
                            @if($program->is_paid)
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    This is a paid program. Registration fee: 
                                    <strong>KES {{ number_format($program->registration_fee, 2) }}</strong>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    This is a free program. No payment required.
                                </div>
                            @endif
                            
                            <div class="d-grid">
                                <a href="{{ route('program.registration.form', $program) }}" 
                                   class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Register Now
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Registration for this program is currently closed.
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <h6>Program Details</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-clock me-2 text-muted"></i> Duration: {{ $program->duration }}</li>
                                <li><i class="bi bi-people me-2 text-muted"></i> Available spots: 
                                    {{ $program->max_participants ? ($program->max_participants - $program->current_participants) : 'Unlimited' }}
                                </li>
                                <li><i class="bi bi-person me-2 text-muted"></i> Created by: {{ $program->user->name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>