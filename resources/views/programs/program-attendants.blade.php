
<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-people me-2"></i>Attendants - {{ $program->title }}
            </h2>
            <div>
                <a href="{{ route('programs.attendants') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-1"></i>All Programs
                </a>
                @if($program->canMarkAttendance())
                    <a href="{{ route('programs.attendants.export', $program) }}" 
                       class="btn btn-success">
                        <i class="bi bi-download me-1"></i>Export PDF
                    </a>
                @endif
            </div>
        </div>
        <div class="container">
            <!-- Program Summary -->
            <div class="dashboard-card mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $program->title }}</h4>
                        <p class="text-muted mb-2">
                            <i class="bi bi-calendar-range me-2"></i>
                            {{ $program->start_date->format('F j, Y \a\t h:i A') }} - 
                            {{ $program->end_date->format('F j, Y \a\t h:i A') }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>{{ $program->venue }}
                        </p>
                        <p class="text-muted">
                            <i class="bi bi-people me-2"></i>
                            Total Confirmed: {{ $confirmedRegistrations->count() }} / 
                            {{ $program->max_participants ?: 'Unlimited' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="alert alert-info">
                            <h6 class="mb-1">Attendance Status</h6>
                            @if($program->canMarkAttendance())
                                <span class="badge bg-success">Active</span>
                                <p class="mb-0 small">Attendance can be marked</p>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                                <p class="mb-0 small">
                                    @if($program->isUpcoming())
                                        Available {{ $program->start_date->subHours(5)->diffForHumans() }}
                                    @elseif($program->isCompleted())
                                        Program completed
                                    @else
                                        Not available yet
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Form -->
            @if($program->canMarkAttendance())
                <div class="dashboard-card mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-list-check me-2"></i>Mark Attendance
                    </h5>
                    <form id="attendance-form" method="POST" action="{{ route('programs.attendants.mark', $program) }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="select-all" class="form-check-input">
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Invitation Code</th>
                                        <th>Status</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($confirmedRegistrations as $registration)
                                        <tr>
                                            <td>
                                                <input type="checkbox" 
                                                       name="attendants[]" 
                                                       value="{{ $registration->id }}"
                                                       class="form-check-input attendant-checkbox"
                                                       {{ $registration->status === 'attended' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <strong>{{ $registration->registrant_name }}</strong>
                                            </td>
                                            <td>{{ $registration->registrant_email }}</td>
                                            <td>{{ $registration->registrant_phone }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $registration->invitation_code }}</span>
                                            </td>
                                            <td>
                                                @if($registration->status === 'confirmed')
                                                    <span class="badge bg-warning">Registered</span>
                                                @elseif($registration->status === 'attended')
                                                    <span class="badge bg-success">Attended</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($registration->status === 'attended')
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle-fill"></i> Present
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="bi bi-clock"></i> Absent
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="bi bi-people display-4 text-muted"></i>
                                                <h5 class="mt-3 text-muted">No confirmed registrations</h5>
                                                <p class="text-muted">There are no confirmed registrations for this program yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($confirmedRegistrations->count() > 0)
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="selected-count">0</span> attendants selected
                                </div>
                                <button type="submit" class="btn btn-primary" id="save-attendance-btn">
                                    <i class="bi bi-check-circle me-1"></i>Save Attendance
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            @else
                <!-- Read-only view when attendance marking is not active -->
                <div class="dashboard-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-people me-2"></i>Confirmed Registrations
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Invitation Code</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($confirmedRegistrations as $registration)
                                    <tr>
                                        <td>
                                            <strong>{{ $registration->registrant_name }}</strong>
                                        </td>
                                        <td>{{ $registration->registrant_email }}</td>
                                        <td>{{ $registration->registrant_phone }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $registration->invitation_code }}</span>
                                        </td>
                                        <td>
                                            @if($registration->status === 'confirmed')
                                                <span class="badge bg-warning">Registered</span>
                                            @elseif($registration->status === 'attended')
                                                <span class="badge bg-success">Attended</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-people display-4 text-muted"></i>
                                            <h5 class="mt-3 text-muted">No confirmed registrations</h5>
                                            <p class="text-muted">There are no confirmed registrations for this program yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($program->canMarkAttendance())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.attendant-checkbox');
            const selectedCount = document.getElementById('selected-count');
            const saveBtn = document.getElementById('save-attendance-btn');

            function updateSelectedCount() {
                const checked = document.querySelectorAll('.attendant-checkbox:checked').length;
                selectedCount.textContent = checked;
                saveBtn.disabled = checked === 0;
            }

            // Select all functionality
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            // Individual checkbox functionality
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Initialize count
            updateSelectedCount();

            // Form submission
            document.getElementById('attendance-form').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.attendant-checkbox:checked').length;
                if (checked === 0) {
                    e.preventDefault();
                    alert('Please select at least one attendant to mark attendance.');
                    return false;
                }
                
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving...';
                saveBtn.disabled = true;
            });
        });
    </script>
    @endif
</x-app-layout>