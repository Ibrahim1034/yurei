
<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-people me-2"></i>Attendees for {{ $event->title }}
            </h2>
            <div>
                <a href="{{ route('invitations.export', $event) }}" class="btn btn-success me-2">
                    <i class="bi bi-download me-1"></i>Export Report
                </a>
                <a href="{{ route('invitations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Invitations
                </a>
            </div>
        </div>
        
        <div class="container">
            <!-- Event Summary Card -->
            <div class="dashboard-card mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-bold">{{ $event->title }}</h4>
                        <p class="text-muted mb-2">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $event->event_date->format('F j, Y \a\t h:i A') }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $event->venue }}
                        </p>
                        <p class="text-muted">
                            <i class="bi bi-people me-1"></i>
                            {{ $registrations->count() }} registered attendees
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="mb-3">
                            @if($event->is_paid)
                                <span class="badge bg-warning fs-6">KES {{ number_format($event->registration_fee, 2) }}</span>
                            @else
                                <span class="badge bg-success fs-6">Free Event</span>
                            @endif
                        </div>
                        
                        <!-- Attendance Stats -->
                        <div class="attendance-stats">
                            @php
                                $attendedCount = $registrations->where('status', \App\Models\EventRegistration::STATUS_ATTENDED)->count();
                                $totalCount = $registrations->count();
                                $attendanceRate = $totalCount > 0 ? round(($attendedCount / $totalCount) * 100) : 0;
                            @endphp
                            <div class="text-success">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $attendedCount }} Attended
                            </div>
                            <div class="text-muted small">
                                {{ $attendanceRate }}% Attendance Rate
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        

            <!-- Attendees List -->
            <div class="dashboard-card">
                <h5 class="fw-bold mb-4">Registered Attendees</h5>

                @if($registrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Invitation Code</th>
                                    <th>Registration Date</th>
                                    <th>Payment Status</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-table-body">
                                @foreach($registrations as $registration)
                                    <tr id="registration-{{ $registration->id }}">
                                        <td>
                                            <strong>{{ $registration->registrant_name }}</strong>
                                            @if($registration->is_guest)
                                                <span class="badge bg-info ms-1">Guest</span>
                                            @endif
                                        </td>
                                        <td>{{ $registration->registrant_email }}</td>
                                        <td>{{ $registration->registrant_phone }}</td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $registration->invitation_code }}</code>
                                        </td>
                                        <td>{{ $registration->registration_date->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($registration->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($registration->canMarkAttendance())
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input attendance-toggle" 
                                                           type="checkbox" 
                                                           data-registration-id="{{ $registration->id }}"
                                                           {{ $registration->status === \App\Models\EventRegistration::STATUS_ATTENDED ? 'checked' : '' }}>
                                                    <label class="form-check-label small">
                                                        {{ $registration->status === \App\Models\EventRegistration::STATUS_ATTENDED ? 'Attended' : 'Mark Attendance' }}
                                                    </label>
                                                </div>
                                            @else
                                                <span class="badge 
                                                    {{ $registration->status === \App\Models\EventRegistration::STATUS_ATTENDED ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $registration->status === \App\Models\EventRegistration::STATUS_ATTENDED ? 'Attended' : 'Not Attended' }}
                                                </span>
                                                @if(!$registration->canMarkAttendance() && !$registration->event->isWithinAttendanceWindow())
                                                    <small class="text-muted d-block">Attendance window closed</small>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">No attendees found</h5>
                        <p class="text-muted">No one has registered for this event yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Refresh attendance data
        function refreshAttendance() {
            fetch(`/admin/invitations/{{ $event->id }}/attendance-data`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateAttendanceStats(data.data);
                        updateAttendanceTable(data.data.registrations);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Update attendance statistics
        function updateAttendanceStats(data) {
            document.getElementById('attended-count').textContent = data.attended_count + ' Attended';
            document.getElementById('total-count').textContent = data.total_registrations + ' Total';
        }

        // Update attendance table
        function updateAttendanceTable(registrations) {
            const tableBody = document.getElementById('attendance-table-body');
            // This would be more complex in a real implementation
            // For now, we'll just refresh the page after 2 seconds if changes are detected
            setTimeout(() => {
                location.reload();
            }, 2000);
        }

        // Handle attendance toggle
        document.addEventListener('DOMContentLoaded', function() {
            const attendanceToggles = document.querySelectorAll('.attendance-toggle');
            
            attendanceToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const registrationId = this.dataset.registrationId;
                    const isChecked = this.checked;
                    
                    if (isChecked) {
                        markAttendance(registrationId);
                    }
                });
            });
        });

        function markAttendance(registrationId) {
            fetch(`/admin/invitations/${registrationId}/mark-attendance`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('Attendance marked successfully!', 'success');
                    // Refresh attendance data
                    refreshAttendance();
                } else {
                    // Show error message
                    showToast(data.error, 'error');
                    // Uncheck the toggle
                    const toggle = document.querySelector(`[data-registration-id="${registrationId}"]`);
                    if (toggle) toggle.checked = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to mark attendance', 'error');
                const toggle = document.querySelector(`[data-registration-id="${registrationId}"]`);
                if (toggle) toggle.checked = false;
            });
        }

        function showToast(message, type) {
            // Simple toast notification
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            toast.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Refresh attendance data every 30 seconds
        setInterval(refreshAttendance, 30000);
        
        // Initial load
        refreshAttendance();
    </script>
</x-app-layout>