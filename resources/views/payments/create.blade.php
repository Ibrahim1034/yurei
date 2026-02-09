<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4>Complete Your Registration</h4>
                            <p class="text-muted">Make payment to activate your membership</p>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Membership Details</h6>
                                <div class="row small mb-3">
                                    <div class="col-4 text-muted">Name:</div>
                                    <div class="col-8">{{ $user->name }}</div>
                                    

                                    
                                    <div class="col-4 text-muted">Phone:</div>
                                    <div class="col-8">{{ $user->phone_number }}</div>
                                </div>

                                <div class="alert alert-info mb-0">
                                    <strong>Membership Fee:</strong> KES {{ $amount ?? 1 }}
                                </div>
                            </div>
                        </div>

                        <!-- Success Message Area -->
                        <div id="success-message" class="alert alert-success d-none">
                            <!-- Message will be inserted here -->
                        </div>

                        <!-- Payment Processing Section (Hidden by default) -->
                        <div id="payment-processing-section" class="text-center mb-3 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Checking payment status...</span>
                            </div>
                            <p class="mt-2" id="status-message">Checking payment status...</p>
                            
                            <!-- Confirm Payment Button -->
                            <div class="mt-3">
                                <button type="button" class="btn btn-warning btn-sm" id="confirm-payment-btn" onclick="confirmPayment()">
                                    <i class="fas fa-sync-alt"></i> Check Payment Status
                                </button>
                            </div>
                        </div>

                        <!-- Error Message Area -->
                        <div id="error-message" class="alert alert-danger d-none">
                            <!-- Error message will be inserted here -->
                        </div>

                        @if (!session('success'))
                            <!-- Pay Button -->
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary btn-lg" id="pay-button" onclick="initiateSTKPush()">
                                    Pay via M-Pesa
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    You will receive an M-Pesa prompt on your phone number: {{ $user->phone_number }}
                                </small>
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                Already paid? Login here
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    let currentCheckoutId = null;
    let isProcessing = false;
    let statusCheckInterval = null;

    function initiateSTKPush() {
        if (isProcessing) return;
        
        isProcessing = true;
        const button = document.getElementById('pay-button');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Initiating...';
        button.disabled = true;

        // Hide any existing messages
        hideAllMessages();

        fetch('{{ route("mpesa.stk.push") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                'user_id': '{{ $user->id }}',
                'amount': '{{ $amount ?? 1 }}',
                'phonenumber': '{{ $user->phone_number }}'
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('STK Push response:', data);
            
            if (data.success) {
                // Show initial success message
                showMessage('success', '✅ M-Pesa prompt sent! Please check your phone and enter your PIN to complete payment.');
                
                // Store checkout ID
                currentCheckoutId = data.checkout_request_id;
                
                // Show processing section
                document.getElementById('payment-processing-section').classList.remove('d-none');
                document.getElementById('status-message').textContent = 'Waiting for payment confirmation...';
                
                // Hide pay button
                button.style.display = 'none';
                
                // Start checking status immediately
                startStatusChecking();
                
            } else {
                showMessage('error', data.error || 'Failed to initiate payment');
                resetButton(button, originalText);
            }
        })
        .catch(error => {
            console.error('STK Push error:', error);
            showMessage('error', 'Network error: ' + error.message);
            resetButton(button, originalText);
        });
    }

    function startStatusChecking() {
        // Clear any existing interval
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        
        // Check status immediately
        checkPaymentStatus();
        
        // Then check every 3 seconds (more frequent)
        statusCheckInterval = setInterval(checkPaymentStatus, 3000);
    }

    function checkPaymentStatus() {
        if (!currentCheckoutId) return;
        
        console.log('Checking payment status for:', currentCheckoutId);
        
        fetch('/v1/status?checkout_request_id=' + currentCheckoutId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Status check response:', data);
            
            if (data.success) {
                handlePaymentStatus(data);
            } else {
                console.error('Status check failed:', data.error);
                // Don't stop checking on temporary errors
            }
        })
        .catch(error => {
            console.error('Status check error:', error);
            // Don't stop checking on network errors
        });
    }

    function handlePaymentStatus(data) {
        const status = data.status;
        const resultDesc = data.result_desc || '';
        
        switch(status) {
            case 'completed':
                // Payment successful - show thank you message and redirect
                showMessage('success', '🎉 Thank you for completing your registration! Your account is now active. Redirecting to login...');
                clearInterval(statusCheckInterval);
                
                // Update status message
                document.getElementById('status-message').textContent = 'Payment completed successfully!';
                document.getElementById('confirm-payment-btn').style.display = 'none';
                
                // Redirect to login after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
                break;
                
            case 'failed':
                // Payment failed
                let errorMessage = 'Payment Failed';
                if (resultDesc.toLowerCase().includes('cancelled')) {
                    errorMessage = 'Payment was cancelled';
                } else if (resultDesc.toLowerCase().includes('timeout') || resultDesc.toLowerCase().includes('timed out')) {
                    errorMessage = 'Payment timed out';
                } else if (resultDesc.toLowerCase().includes('insufficient')) {
                    errorMessage = 'Insufficient funds';
                }
                
                showMessage('error', errorMessage);
                clearInterval(statusCheckInterval);
                
                // Show retry option
                setTimeout(() => {
                    location.reload();
                }, 3000);
                break;
                
            case 'pending':
                // Still waiting - update status message
                document.getElementById('status-message').textContent = 'Waiting for payment confirmation...';
                break;
                
            default:
                // Unknown status - keep checking
                document.getElementById('status-message').textContent = 'Processing payment...';
                break;
        }
    }

    function confirmPayment() {
        if (!currentCheckoutId) {
            showMessage('error', 'No payment session found');
            return;
        }

        const button = document.getElementById('confirm-payment-btn');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Checking...';
        button.disabled = true;

        fetch('{{ route("mpesa.stk.query") }}', {
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
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('STK Query response:', data);
            
            if (data.success) {
                const result = data.result;
                
                if (result.ResultCode == '0') {
                    showMessage('success', '🎉 Thank you for completing your registration! Your account is now active.');
                    clearInterval(statusCheckInterval);
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    const resultDesc = result.ResultDesc || '';
                    let errorMessage = 'Payment Failed';
                    if (resultDesc.toLowerCase().includes('cancelled')) {
                        errorMessage = 'Payment was cancelled';
                    } else if (resultDesc.toLowerCase().includes('timeout') || resultDesc.toLowerCase().includes('timed out')) {
                        errorMessage = 'Payment timed out';
                    }
                    showMessage('error', errorMessage);
                    setTimeout(() => location.reload(), 3000);
                }
            } else {
                showMessage('error', 'Failed to check payment status');
                resetConfirmButton(button, originalText);
            }
        })
        .catch(error => {
            console.error('STK Query error:', error);
            showMessage('error', 'Network error checking payment');
            resetConfirmButton(button, originalText);
        });
    }

    function showMessage(type, message) {
        // Hide all messages first
        hideAllMessages();
        
        let messageDiv;
        if (type === 'success') {
            messageDiv = document.getElementById('success-message');
        } else {
            messageDiv = document.getElementById('error-message');
        }
        
        messageDiv.innerHTML = message + '<button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>';
        messageDiv.classList.remove('d-none');
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.classList.add('d-none');
            }, 5000);
        }
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

    // Clean up interval when leaving page
    window.addEventListener('beforeunload', function() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
    });
</script>
</x-guest-layout>