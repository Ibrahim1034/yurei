<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-people me-2"></i>Program Attendants
            </h2>
            <div>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-1"></i>Back to Programs
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

            <div class="row">
                @forelse($programs as $program)
                    @php
                        $confirmedRegistrations = $program->registrations()
                            ->where(function($query) {
                                $query->where('status', 'confirmed')
                                      ->orWhere('status', 'attended');
                            })
                            ->count();
                    @endphp
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
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-2">
                                        <span><i class="bi bi-calendar me-1"></i> {{ $program->duration }}</span>
                                        <span><i class="bi bi-people me-1"></i> {{ $confirmedRegistrations }}/{{ $program->max_participants ?: '∞' }}</span>
                                    </div>
                                    
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-clock me-1"></i> 
                                        {{ $program->start_date->format('M d, Y \a\t h:i A') }}
                                    </div>
                                    
                                    <div class="small text-muted">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $program->venue }}
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('programs.attendants.show', $program) }}" 
                                           class="btn btn-primary">
                                            <i class="bi bi-list-check me-1"></i>
                                            View Attendants ({{ $confirmedRegistrations }})
                                        </a>
                                        
                                        @if($program->canMarkAttendance() || $program->isCompleted())
                                            <a href="{{ route('programs.attendants.export', $program) }}" 
                                               class="btn btn-outline-success">
                                                <i class="bi bi-download me-1"></i>
                                                Export PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($program->isUpcoming())
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($program->isOngoing())
                                            <span class="badge bg-success">Ongoing</span>
                                        @elseif($program->isCompleted())
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </small>
                                    <small class="text-muted">
                                        @if($program->canMarkAttendance())
                                            <span class="badge bg-warning">Attendance Active</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <h3 class="mt-3 text-muted">No Programs Available</h3>
                            <p class="text-muted">There are no programs with registered attendants.</p>
                            <a href="{{ route('programs.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i>Back to Programs
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>