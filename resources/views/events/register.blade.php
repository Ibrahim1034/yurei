<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-calendar-plus me-2"></i>Register for Event
            </h2>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="dashboard-card">
                        <div class="text-center mb-4">
                            @if($event->image)
                                <img src="{{ Storage::disk('public')->url($event->image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="rounded mb-3"
                                     style="width: 100%; height: 200px; object-fit: cover;">
                            @endif
                            <h4>{{ $event->title }}</h4>
                            <p class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $event->event_date->format('M d, Y h:i A') }}
                            </p>
                            <p class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $event->venue }}
                            </p>
                        </div>

                        <!-- Registration Form -->
                        <form id="registration-form">
                            @csrf
                            
                            <!-- Guest Information Fields (shown when not authenticated) -->
                            @if(!auth()->check())
                                <div class="mb-4 p-3 border rounded bg-light">
                                    <h6 class="mb-3"><i class="bi bi-person me-2"></i>Your Information</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="guest_name" class="form-label">Full Name *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="guest_name" 
                                                       name="guest_name" 
                                                       placeholder="Enter your full name" 
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="guest_email" class="form-label">Email Address *</label>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="guest_email" 
                                                       name="guest_email" 
                                                       placeholder="Enter your email" 
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="guest_phone" class="form-label">Phone Number *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="guest_phone" 
                                               name="guest_phone" 
                                               placeholder="2547XXXXXXXX" 
                                               required>
                                        <div class="form-text">We'll send event updates to this number</div>
                                    </div>
                                </div>
                            @endif

                            @if($event->is_paid)
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle me-2"></i>Paid Event</h6>
                                    <p class="mb-0">Registration Fee: <strong>KES {{ number_format($event->registration_fee, 2) }}</strong></p>
                                </div>

                                <!-- Payment Information -->
                              <div class="mb-3">
                                    <label for="phone_number" class="form-label">M-Pesa Phone Number *</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="phone_number" 
                                        name="phone_number" 
                                        value="{{ auth()->check() ? (auth()->user()->phone_number ?? '') : '' }}"
                                        placeholder="2547XXXXXXXX" 
                                        required>
                                    <div class="form-text">Enter your M-Pesa registered phone number starting with 254</div>
                                </div>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary btn-lg" id="pay-button" onclick="initiateRegistration()">
                                        Pay & Register
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <h6><i class="bi bi-check-circle me-2"></i>Free Event</h6>
                                    <p class="mb-0">No payment required for registration</p>
                                </div>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-success btn-lg" onclick="registerForFreeEvent()">
                                        Register Now
                                    </button>
                                </div>
                            @endif

                            <!-- Login Prompt for Guests -->
                            @if(!auth()->check())
                                <div class="mt-3 text-center">
                                    <p class="text-muted small">
                                        Already have an account? 
                                        <a href="{{ route('login') }}?redirect={{ url()->current() }}">Login here</a>
                                    </p>
                                </div>
                            @endif
                        </form>

                        <!-- Payment Processing Section -->
                        <div id="payment-processing-section" class="text-center mt-4 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Processing...</span>
                            </div>
                            <p class="mt-2" id="status-message">Processing registration...</p>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-warning btn-sm" id="confirm-payment-btn" onclick="confirmPayment()">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Check Payment Status
                                </button>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="success-message" class="alert alert-success d-none mt-3"></div>
                        <div id="error-message" class="alert alert-danger d-none mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>


            // In your register.blade.php - Replace the entire JavaScript section
            let currentCheckoutId = null;
            let currentRegistrationId = null;
            let isProcessing = false;
            let statusCheckInterval = null;

            // In register.blade.php - Update the registerForFreeEvent function
            function registerForFreeEvent() {
                if (isProcessing) return;
                
                isProcessing = true;
                const button = document.querySelector('#payment-processing-section').previousElementSibling.querySelector('button');
                const originalText = button.innerHTML;
                
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Registering...';
                button.disabled = true;

                hideAllMessages();

                // Collect registration data
                const registrationData = {
                    _token: '{{ csrf_token() }}'
                };

                // Add guest information if not authenticated
                @if(!auth()->check())
                    registrationData.guest_name = document.getElementById('guest_name').value;
                    registrationData.guest_email = document.getElementById('guest_email').value;
                    registrationData.guest_phone = document.getElementById('guest_phone').value;
                    
                    // Validate guest data
                    if (!registrationData.guest_name || !registrationData.guest_email || !registrationData.guest_phone) {
                        showMessage('error', 'Please fill in all your information.');
                        resetButton(button, originalText);
                        return;
                    }
                @endif

                fetch('{{ route("event.registration.process", $event->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(registrationData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store success message in sessionStorage for welcome page
                        sessionStorage.setItem('paymentSuccess', 'true');
                        sessionStorage.setItem('registrationMessage', 'Registration successful! Check your email for the event invitation.');
                        
                        // Redirect to welcome page after 2 seconds
                        setTimeout(() => {
                            window.location.href = '{{ url("/") }}';
                        }, 2000);
                    } else {
                        showMessage('error', data.error);
                        resetButton(button, originalText);
                    }
                })
                .catch(error => {
                    console.error('Registration error:', error);
                    showMessage('error', 'Registration failed: ' + error.message);
                    resetButton(button, originalText);
                });
            }



            // In register.blade.php - Simplified initiateRegistration function

            function initiateRegistration() {
                if (isProcessing) return;
                
                isProcessing = true;
                const button = document.getElementById('pay-button');
                const originalText = button.innerHTML;
                
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
                button.disabled = true;

                hideAllMessages();

                // Collect registration data
                const registrationData = {
                    _token: '{{ csrf_token() }}'
                };

                // Add guest information if not authenticated
                @if(!auth()->check())
                    registrationData.guest_name = document.getElementById('guest_name').value;
                    registrationData.guest_email = document.getElementById('guest_email').value;
                    registrationData.guest_phone = document.getElementById('guest_phone').value;
                    
                    // Validate guest data
                    if (!registrationData.guest_name || !registrationData.guest_email || !registrationData.guest_phone) {
                        showMessage('error', 'Please fill in all your information.');
                        resetButton(button, originalText);
                        return;
                    }
                @endif

                // Add payment phone number for paid events
                if ({{ $event->is_paid ? 'true' : 'false' }}) {
                    registrationData.phone_number = document.getElementById('phone_number').value;
                    if (!registrationData.phone_number) {
                        showMessage('error', 'Please enter your M-Pesa phone number.');
                        resetButton(button, originalText);
                        return;
                    }
                }

                // Always use the same registration endpoint - it will handle pending registrations automatically
                fetch('{{ route("event.registration.process", $event->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(registrationData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentRegistrationId = data.registration_id;
                        
                        if (data.free_event) {
                            // Free event - registration complete
                            showMessage('success', data.message);
                            setTimeout(() => {
                                window.location.href = getSuccessUrl(currentRegistrationId);
                            }, 2000);
                        } else {
                            // Paid event - initiate payment automatically
                            initiatePayment();
                        }
                    } else {
                        // Only show error if it's not a pending registration (which should be handled automatically)
                        showMessage('error', data.error);
                        resetButton(button, originalText);
                    }
                })
                .catch(error => {
                    console.error('Registration error:', error);
                    showMessage('error', 'Registration failed: ' + error.message);
                    resetButton(button, originalText);
                });
            }

            // Rest of your functions remain the same...
            function initiatePayment() {
                const phoneNumber = document.getElementById('phone_number').value;
                
                if (!phoneNumber) {
                    showMessage('error', 'Please enter your phone number');
                    resetProcessing();
                    return;
                }

                document.getElementById('payment-processing-section').classList.remove('d-none');
                document.getElementById('status-message').textContent = 'Initiating payment...';

                fetch('{{ route("event.payment.initiate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        'registration_id': currentRegistrationId,
                        'phone_number': phoneNumber
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentCheckoutId = data.checkout_request_id;
                        document.getElementById('status-message').textContent = 'M-Pesa prompt sent. Please check your phone.';
                        startStatusChecking();
                    } else {
                        showMessage('error', data.error);
                        resetProcessing();
                    }
                })
                .catch(error => {
                    console.error('Payment initiation error:', error);
                    showMessage('error', 'Payment initiation failed: ' + error.message);
                    resetProcessing();
                });
            }


            function initiatePayment() {
                const phoneNumber = document.getElementById('phone_number').value;
                
                if (!phoneNumber) {
                    showMessage('error', 'Please enter your phone number');
                    return;
                }

                document.getElementById('payment-processing-section').classList.remove('d-none');
                document.getElementById('status-message').textContent = 'Initiating payment...';

                fetch('{{ route("event.payment.initiate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        'registration_id': currentRegistrationId,
                        'phone_number': phoneNumber
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentCheckoutId = data.checkout_request_id;
                        document.getElementById('status-message').textContent = 'M-Pesa prompt sent. Please check your phone.';
                        startStatusChecking();
                    } else {
                        showMessage('error', data.error);
                        resetProcessing();
                    }
                })
                .catch(error => {
                    console.error('Payment initiation error:', error);
                    showMessage('error', 'Payment initiation failed: ' + error.message);
                    resetProcessing();
                });
            }

            function startStatusChecking() {
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
                
                checkPaymentStatus();
                statusCheckInterval = setInterval(checkPaymentStatus, 5000);
            }

            function checkPaymentStatus() {
                if (!currentCheckoutId) return;
                
                // Use the same status endpoint as working system
                fetch('{{ route("event.payment.status") }}?checkout_request_id=' + currentCheckoutId, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        handlePaymentStatus(data);
                    } else {
                        console.error('Status check failed:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Status check error:', error);
                });
            }

            // In register.blade.php - Update the handlePaymentStatus function
            function handlePaymentStatus(data) {
                const status = data.status;
                const resultDesc = data.result_desc || '';
                
                switch(status) {
                    case 'completed':
                        showMessage('success', 'Payment successful! Registration confirmed. Check your email for confirmation.');
                        clearInterval(statusCheckInterval);
                        
                        // Store success message in sessionStorage for welcome page
                        sessionStorage.setItem('paymentSuccess', 'true');
                        sessionStorage.setItem('registrationMessage', 'Payment successful! Check your email for the event invitation.');
                        
                        // Redirect to welcome page after 2 seconds
                        setTimeout(() => {
                            window.location.href = '{{ url("/") }}';
                        }, 2000);
                        break;
                        
                    case 'failed':
                        if (resultDesc.toLowerCase().includes('cancelled')) {
                            showMessage('error', 'Payment cancelled by user');
                        } else if (resultDesc.toLowerCase().includes('timeout')) {
                            showMessage('error', 'Payment timed out');
                        } else {
                            showMessage('error', 'Payment failed: ' + resultDesc);
                        }
                        clearInterval(statusCheckInterval);
                        
                        // Store failure message for reload
                        sessionStorage.setItem('paymentFailed', 'true');
                        
                        // Reload page after 3 seconds for user to try again
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                        break;
                        
                    case 'pending':
                        document.getElementById('status-message').textContent = 'Waiting for payment confirmation...';
                        break;
                        
                    default:
                        document.getElementById('status-message').textContent = 'Processing payment...';
                        break;
                }
            }

            // Also update the STK query success case
            function confirmPayment() {
                if (!currentCheckoutId) {
                    showMessage('error', 'No payment session found');
                    return;
                }

                const button = document.getElementById('confirm-payment-btn');
                const originalText = button.innerHTML;
                
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Checking...';
                button.disabled = true;

                fetch('{{ route("event.payment.query") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        'checkout_request_id': currentCheckoutId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.status === 'completed') {
                            showMessage('success', 'Payment successful! Registration confirmed. Check your email for confirmation.');
                            
                            // Store success message in sessionStorage
                            sessionStorage.setItem('paymentSuccess', 'true');
                            sessionStorage.setItem('registrationMessage', 'Payment successful! Check your email for the event invitation.');
                            
                            // Redirect to welcome page
                            setTimeout(() => {
                                window.location.href = '{{ url("/") }}';
                            }, 2000);
                        } else {
                            handlePaymentStatus(data);
                        }
                    } else {
                        showMessage('error', 'Failed to check payment: ' + data.error);
                        resetConfirmButton(button, originalText);
                    }
                })
                .catch(error => {
                    console.error('Payment confirmation error:', error);
                    showMessage('error', 'Network error checking payment');
                    resetConfirmButton(button, originalText);
                });
            }
            // Rest of your helper functions remain the same...

            function showMessage(type, message) {
                hideAllMessages();
                
                let messageDiv;
                if (type === 'success') {
                    messageDiv = document.getElementById('success-message');
                } else {
                    messageDiv = document.getElementById('error-message');
                }
                
                messageDiv.innerHTML = message;
                messageDiv.classList.remove('d-none');
            }

            function hideAllMessages() {
                document.getElementById('success-message').classList.add('d-none');
                document.getElementById('error-message').classList.add('d-none');
            }

            function resetButton(button, originalText) {
                isProcessing = false;
                button.innerHTML = originalText;
                button.disabled = false;
            }

            function resetConfirmButton(button, originalText) {
                button.innerHTML = originalText;
                button.disabled = false;
            }

            function resetProcessing() {
                isProcessing = false;
                const button = document.getElementById('pay-button');
                if (button) {
                    button.innerHTML = 'Pay & Register';
                    button.disabled = false;
                }
                document.getElementById('payment-processing-section').classList.add('d-none');
            }

            function getSuccessUrl(registrationId) {
                return `/events/registration/success/${registrationId}`;
            }

            window.addEventListener('beforeunload', function() {
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
            });
</script>
</x-app-layout>