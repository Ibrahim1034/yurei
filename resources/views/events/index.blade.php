<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-calendar-event me-2"></i>Events Management
            </h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Create Event
            </a>
        </div>
        
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

                 <!-- Quick Action Button -->
            <div class="text-end mb-3">
                <a href="{{ route('events.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Add New Event
                </a>
            </div>

            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Type</th>
                                <th>Participants</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td>
                                        @if($event->image)
                                            <img src="{{ Storage::disk('public')->url($event->image) }}" 
                                                alt="{{ $event->title }}" 
                                                class="rounded"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="bi bi-calendar-event text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $event->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                    </td>
                                    <td>
                                        {{ $event->event_date->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $event->event_date->format('h:i A') }}</small>
                                    </td>
                                    <td>{{ $event->venue }}</td>
                                    <td>
                                        @if($event->is_paid)
                                            <span class="badge bg-warning">KES {{ number_format($event->registration_fee, 2) }}</span>
                                        @else
                                            <span class="badge bg-success">Free</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $event->current_participants }}/{{ $event->max_participants ?: '∞' }}
                                    </td>
                                    <td>
                                        @if($event->isRegistrationOpen())
                                            <span class="badge bg-success">Open</span>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ $event->user->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('events.edit', $event) }}" 
                                            class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" 
                                                onsubmit="return confirm('Are you sure you want to delete this event?');">
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
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                                        <h5 class="mt-3 text-muted">No events found</h5>
                                        <p class="text-muted">Get started by creating your first event.</p>
                                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i>Create Event
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