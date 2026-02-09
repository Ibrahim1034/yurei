
<x-app-layout>
    <x-slot name="header">
      
    </x-slot>

    <div class="py-4">
          <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 10%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-send me-2"></i>Assign Event Invitations
            </h2>
            <a href="{{ route('invitations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Invitations
            </a>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <form action="{{ route('invitations.send') }}" method="POST" id="invitation-form">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="event_id" class="form-label">Select Event *</label>
                                <select class="form-control @error('event_id') is-invalid @enderror" 
                                        id="event_id" 
                                        name="event_id" 
                                        required>
                                    <option value="">Choose an event...</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" 
                                                {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->title }} - {{ $event->event_date->format('M d, Y') }}
                                            @if($event->is_paid)
                                                (KES {{ number_format($event->registration_fee, 2) }})
                                            @else
                                                (Free)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Members Section -->
                            <div class="mb-4">
                                <label class="form-label">Select Members to Invite</label>
                                <small class="text-muted d-block mb-2">Choose from existing YUREI members</small>
                                
                                <!-- Quick Select Buttons -->
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectAllUsers()">
                                        Select All Members
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAllUsers()">
                                        Deselect All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectActiveUsers()">
                                        Select Active Only
                                    </button>
                                </div>
                                
                                <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
                                    <div class="p-3">
                                        @foreach($users as $user)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input user-checkbox" 
                                                       type="checkbox" 
                                                       name="user_ids[]" 
                                                       value="{{ $user->id }}" 
                                                       id="user_{{ $user->id }}"
                                                       {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                    <strong>{{ $user->name }}</strong>
                                                    <small class="text-muted"> - {{ $user->email }}</small>
                                                    @if(!$user->is_active)
                                                        <span class="badge bg-warning ms-1">Inactive</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('user_ids')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- External Emails Section -->
                            <div class="mb-4">
                                <label for="external_emails" class="form-label">Invite External Guests</label>
                                <small class="text-muted d-block mb-2">Enter email addresses of non-members (separate multiple emails with commas, semicolons, or new lines)</small>
                                
                                <textarea class="form-control @error('external_emails') is-invalid @enderror" 
                                          id="external_emails" 
                                          name="external_emails" 
                                          rows="4" 
                                          placeholder="e.g., guest1@example.com, guest2@example.com&#10;guest3@example.com">{{ old('external_emails') }}</textarea>
                                @error('external_emails')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    These guests will receive invitation emails and can register as external attendees.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="custom_message" class="form-label">Custom Message (Optional)</label>
                                <textarea class="form-control @error('custom_message') is-invalid @enderror" 
                                          id="custom_message" 
                                          name="custom_message" 
                                          rows="4" 
                                          placeholder="Add a personal message for the invitation...">{{ old('custom_message') }}</textarea>
                                @error('custom_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">This message will be included in all invitation emails.</div>
                            </div>

                            <!-- Selected Users Preview -->
                            <div class="mb-4" id="selected-users-preview" style="display: none;">
                                <h6>Selected Recipients: <span id="selected-count">0</span></h6>
                                <div id="selected-users-list" class="small text-muted"></div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('invitations.index') }}" class="btn btn-outline-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="send-button">
                                    <i class="bi bi-send me-1"></i>Send Invitations
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User selection functions
        function selectAllUsers() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedUsersPreview();
        }

        function deselectAllUsers() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedUsersPreview();
        }

        function selectActiveUsers() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                // This is a simplified version - you might want to add data attributes for active status
                const label = checkbox.nextElementSibling;
                if (!label.querySelector('.badge.bg-warning')) {
                    checkbox.checked = true;
                }
            });
            updateSelectedUsersPreview();
        }

        // Update selected users preview
        function updateSelectedUsersPreview() {
            const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
            const externalEmails = document.getElementById('external_emails').value;
            const externalEmailCount = externalEmails ? externalEmails.split(/[\s,;]+/).filter(email => email.trim().length > 0).length : 0;
            
            const totalCount = selectedCheckboxes.length + externalEmailCount;
            const preview = document.getElementById('selected-users-preview');
            const countSpan = document.getElementById('selected-count');
            const listDiv = document.getElementById('selected-users-list');
            
            countSpan.textContent = totalCount;
            
            if (totalCount > 0) {
                let userList = '';
                
                // Show selected members (first 3)
                if (selectedCheckboxes.length > 0) {
                    userList += '<div><strong>Members:</strong></div>';
                    selectedCheckboxes.forEach((checkbox, index) => {
                        if (index < 3) {
                            const label = checkbox.nextElementSibling.textContent.trim();
                            userList += `<div>${label}</div>`;
                        }
                    });
                    if (selectedCheckboxes.length > 3) {
                        userList += `<div>... and ${selectedCheckboxes.length - 3} more members</div>`;
                    }
                }
                
                // Show external emails count
                if (externalEmailCount > 0) {
                    if (selectedCheckboxes.length > 0) {
                        userList += '<div class="mt-2"><strong>External Guests:</strong></div>';
                    } else {
                        userList += '<div><strong>External Guests:</strong></div>';
                    }
                    userList += `<div>${externalEmailCount} external email(s)</div>`;
                }
                
                listDiv.innerHTML = userList;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Update preview when checkboxes change
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedUsersPreview);
            });
            
            // Update preview when external emails change
            document.getElementById('external_emails').addEventListener('input', updateSelectedUsersPreview);
            
            // Initial update
            updateSelectedUsersPreview();
            
            // Form submission
            document.getElementById('invitation-form').addEventListener('submit', function(e) {
                const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
                const externalEmails = document.getElementById('external_emails').value;
                const externalEmailCount = externalEmails ? externalEmails.split(/[\s,;]+/).filter(email => email.trim().length > 0).length : 0;
                const sendButton = document.getElementById('send-button');
                
                if (selectedCount === 0 && externalEmailCount === 0) {
                    e.preventDefault();
                    alert('Please select at least one member or enter external emails to send invitations.');
                    return;
                }
                
                // Show loading state
                sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Sending...';
                sendButton.disabled = true;
            });
        });
    </script>
</x-app-layout>