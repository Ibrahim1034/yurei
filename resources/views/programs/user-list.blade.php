<x-app-layout>
   

    <div class="py-4">
         
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-calendar-range me-2"></i>Available Programs
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                @forelse($programs as $program)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($program->image)
                                <img src="{{ Storage::disk('public')->url($program->image) }}" 
                                     alt="{{ $program->title }}" 
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="bi bi-calendar-range text-muted display-4"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ $program->title }}</h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($program->description, 100) }}
                                </p>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-2">
                                        <span><i class="bi bi-calendar me-1"></i> {{ $program->duration }}</span>
                                        <span><i class="bi bi-people me-1"></i> {{ $program->current_participants }}/{{ $program->max_participants ?: '∞' }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between small text-muted mb-2">
                                        <span><i class="bi bi-geo-alt me-1"></i> {{ $program->venue }}</span>
                                        @if($program->is_paid)
                                            <span class="text-warning fw-bold">KES {{ number_format($program->registration_fee, 2) }}</span>
                                        @else
                                            <span class="text-success fw-bold">Free</span>
                                        @endif
                                    </div>
                                    
                                    <div class="small text-muted">
                                        <i class="bi bi-clock me-1"></i> 
                                        {{ $program->start_date->format('M d, Y') }} - {{ $program->end_date->format('M d, Y') }}
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    @if($program->isRegistrationOpen())
                                        <a href="{{ route('program.registration.form', $program) }}" 
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-person-plus me-1"></i>
                                            Register Now
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="bi bi-lock me-1"></i>
                                            Registration Closed
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('programs.show', $program) }}" 
                                       class="btn btn-outline-secondary w-100 mt-2">
                                        <i class="bi bi-eye me-1"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($program->isUpcoming())
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($program->isOngoing())
                                            <span class="badge bg-success">Ongoing</span>
                                        @endif
                                    </small>
                                    <small class="text-muted">
                                        Posted by YUREI ADMIN
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-range display-1 text-muted"></i>
                            <h3 class="mt-3 text-muted">No Programs Available</h3>
                            <p class="text-muted">There are no programs available for registration at the moment.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>