
<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-calendar-range me-2"></i>Programs Management
            </h2>
            <a href="{{ route('programs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Create Program
            </a>
        </div>
        
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Quick Action Buttons -->
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('programs.attendants') }}" class="btn btn-info">
                    <i class="bi bi-people me-1"></i>View All Attendants
                </a>
                <a href="{{ route('programs.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Add New Program
                </a>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Dates</th>
                                <th>Venue</th>
                                <th>Type</th>
                                <th>Participants</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programs as $program)
                                <tr>
                                    <td>
                                        @if($program->image)
                                            <img src="{{ Storage::disk('public')->url($program->image) }}" 
                                                alt="{{ $program->title }}" 
                                                class="rounded"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="bi bi-calendar-range text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $program->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($program->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $program->duration ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        {{ $program->start_date->format('M d, Y') }} 
                                        <br>
                                        <small class="text-muted">to {{ $program->end_date->format('M d, Y') }}</small>
                                    </td>
                                    <td>{{ $program->venue }}</td>
                                    <td>
                                        @if($program->is_paid)
                                            <span class="badge bg-warning">KES {{ number_format($program->registration_fee, 2) }}</span>
                                        @else
                                            <span class="badge bg-success">Free</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $program->current_participants }}/{{ $program->max_participants ?: '∞' }}
                                    </td>
                                    <td>
                                        @if($program->isUpcoming())
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($program->isOngoing())
                                            <span class="badge bg-success">Ongoing</span>
                                        @elseif($program->isCompleted())
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                        
                                        @if($program->isRegistrationOpen())
                                            <span class="badge bg-success mt-1">Registration Open</span>
                                        @else
                                            <span class="badge bg-danger mt-1">Registration Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ $program->user->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('programs.attendants.show', $program) }}" 
                                               class="btn btn-outline-info btn-sm"
                                               title="View Attendants">
                                                <i class="bi bi-people"></i>
                                            </a>
                                            <a href="{{ route('programs.edit', $program) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('programs.destroy', $program) }}" method="POST" 
                                                onsubmit="return confirm('Are you sure you want to delete this program?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="bi bi-calendar-range display-4 text-muted"></i>
                                        <h5 class="mt-3 text-muted">No programs found</h5>
                                        <p class="text-muted">Get started by creating your first program.</p>
                                        <a href="{{ route('programs.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i>Create Program
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>