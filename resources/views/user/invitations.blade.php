<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-envelope me-2"></i>My Invitations
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="dashboard-card">
                <h4 class="fw-bold mb-4">My Event Invitations</h4>

                @if($invitations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Invitation Code</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $invitation)
                                    <tr>
                                        <td>
                                            <strong>{{ $invitation->event->title }}</strong>
                                        </td>
                                        <td>{{ $invitation->event->event_date->format('M d, Y h:i A') }}</td>
                                        <td>{{ $invitation->event->venue }}</td>
                                        <td>
                                            <code class="bg-light p-2 rounded">{{ $invitation->invitation_code }}</code>
                                        </td>
                                        <td>
                                            @if($invitation->status === \App\Models\EventRegistration::STATUS_ATTENDED)
                                                <span class="badge bg-success">Attended</span>
                                            @else
                                                <span class="badge bg-primary">Registered</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('event.registration.success', $invitation) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-envelope display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No invitations yet</h5>
                        <p class="text-muted">You haven't received any event invitations yet.</p>
                        <a href="{{ url('/#events') }}" class="btn btn-primary">
                            <i class="bi bi-calendar-event me-1"></i>Browse Events
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>