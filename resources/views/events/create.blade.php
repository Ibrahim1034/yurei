<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-plus-circle me-2"></i>Create New Event
            </h2>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Events
            </a>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="dashboard-card">
                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Event Title *</label>
                                        <input type="text" 
                                               class="form-control @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title') }}" 
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="event_date" class="form-label">Event Date & Time *</label>
                                        <input type="datetime-local" 
                                               class="form-control @error('event_date') is-invalid @enderror" 
                                               id="event_date" 
                                               name="event_date" 
                                               value="{{ old('event_date') }}" 
                                               required>
                                        @error('event_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="venue" class="form-label">Venue *</label>
                                <input type="text" 
                                       class="form-control @error('venue') is-invalid @enderror" 
                                       id="venue" 
                                       name="venue" 
                                       value="{{ old('venue') }}" 
                                       required>
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Event Image</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_paid" class="form-label">Event Type</label>
                                        <select class="form-control @error('is_paid') is-invalid @enderror" 
                                                id="is_paid" 
                                                name="is_paid" 
                                                onchange="togglePaymentFields()">
                                            <option value="0" {{ old('is_paid') == '0' ? 'selected' : '' }}>Free Event</option>
                                            <option value="1" {{ old('is_paid') == '1' ? 'selected' : '' }}>Paid Event</option>
                                        </select>
                                        @error('is_paid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3" id="registration_fee_field" style="display: none;">
                                        <label for="registration_fee" class="form-label">Registration Fee (KES)</label>
                                        <input type="number" 
                                            class="form-control @error('registration_fee') is-invalid @enderror" 
                                            id="registration_fee" 
                                            name="registration_fee" 
                                            value="{{ old('registration_fee', 0) }}" 
                                            min="0" 
                                            step="0.01">
                                        @error('registration_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_participants" class="form-label">Maximum Participants</label>
                                        <input type="number" 
                                            class="form-control @error('max_participants') is-invalid @enderror" 
                                            id="max_participants" 
                                            name="max_participants" 
                                            value="{{ old('max_participants', 0) }}" 
                                            min="0">
                                        @error('max_participants')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Set to 0 for unlimited participants</div>
                                    </div>
                                </div>
                                
                               <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_deadline" class="form-label">Registration Deadline</label>
                                    <input type="datetime-local" 
                                        class="form-control @error('registration_deadline') is-invalid @enderror" 
                                        id="registration_deadline" 
                                        name="registration_deadline" 
                                        value="{{ old('registration_deadline', isset($event) ? ($event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '') : '') }}">
                                    @error('registration_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave empty for no deadline</div>
                                </div>
                            </div>
                                                        </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Create Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePaymentFields() {
            const isPaid = document.getElementById('is_paid').value;
            const feeField = document.getElementById('registration_fee_field');
            
            if (isPaid === '1') {
                feeField.style.display = 'block';
                document.getElementById('registration_fee').required = true;
            } else {
                feeField.style.display = 'none';
                document.getElementById('registration_fee').required = false;
                document.getElementById('registration_fee').value = 0;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentFields();
        });
        </script>
</x-app-layout>