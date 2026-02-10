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
    let paymentAttempts = 0;
    const MAX_PAYMENT_ATTEMPTS = 3;

    // FIXED: Added async/await and proper promise handling
    async function initiateSTKPush() {
        if (isProcessing) return;
        
        paymentAttempts++;
        if (paymentAttempts > MAX_PAYMENT_ATTEMPTS) {
            showMessage('error', 'Too many payment attempts. Please refresh the page and try again.');
            return;
        }
        
        isProcessing = true;
        const button = document.getElementById('pay-button');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Initiating...';
        button.disabled = true;

        // Hide any existing messages
        hideAllMessages();

        try {
            // FIXED: Add timeout to prevent hanging promises
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout

            const response = await fetch('{{ route("mpesa.stk.push") }}', {
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
                }),
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            console.log('Response status:', response.status);
            
            // FIXED: Handle authentication errors
            if (response.status === 401 || response.status === 403) {
                window.location.href = '{{ route("login") }}';
                return;
            }
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
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
        } catch (error) {
            console.error('STK Push error:', error);
            
            // FIXED: Better error messages
            let errorMessage = 'Network error';
            if (error.name === 'AbortError') {
                errorMessage = 'Request timed out. Please try again.';
            } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'Network connection failed. Please check your internet.';
            } else {
                errorMessage = error.message || 'Failed to initiate payment';
            }
            
            showMessage('error', errorMessage);
            resetButton(button, originalText);
        }
    }

    function startStatusChecking() {
        // Clear any existing interval
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        
        // Check status immediately
        checkPaymentStatus();
        
        // FIXED: Reduced frequency to prevent rate limiting
        statusCheckInterval = setInterval(checkPaymentStatus, 5000); // 5 seconds
    }

    // FIXED: Added async/await and error handling
    async function checkPaymentStatus() {
        if (!currentCheckoutId) return;
        
        console.log('Checking payment status for:', currentCheckoutId);
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 8000); // 8 second timeout

            const response = await fetch('/v1/status?checkout_request_id=' + currentCheckoutId, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Status check response:', data);
            
            if (data.success) {
                handlePaymentStatus(data);
            } else {
                console.error('Status check failed:', data.error);
                // Don't stop checking on temporary errors
            }
        } catch (error) {
            console.error('Status check error:', error);
            // Don't stop checking on network errors
        }
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
                const retryBtn = document.createElement('button');
                retryBtn.className = 'btn btn-primary btn-sm mt-2';
                retryBtn.textContent = 'Try Again';
                retryBtn.onclick = () => location.reload();
                
                document.getElementById('error-message').appendChild(retryBtn);
                break;
                
            case 'pending':
                // Still waiting - update status message
                const elapsed = Math.floor((Date.now() - window.paymentStartTime) / 1000);
                document.getElementById('status-message').textContent = `Waiting for payment confirmation... (${elapsed}s)`;
                break;
                
            default:
                // Unknown status - keep checking
                document.getElementById('status-message').textContent = 'Processing payment...';
                break;
        }
    }

    // FIXED: Added async/await
    async function confirmPayment() {
        if (!currentCheckoutId) {
            showMessage('error', 'No payment session found');
            return;
        }

        const button = document.getElementById('confirm-payment-btn');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Checking...';
        button.disabled = true;

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);

            const response = await fetch('{{ route("mpesa.stk.query") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    'checkout_request_id': currentCheckoutId
                }),
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
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
                    
                    // Add retry button
                    const retryBtn = document.createElement('button');
                    retryBtn.className = 'btn btn-primary btn-sm mt-2';
                    retryBtn.textContent = 'Try Again';
                    retryBtn.onclick = () => location.reload();
                    
                    document.getElementById('error-message').appendChild(retryBtn);
                }
            } else {
                showMessage('error', data.error || 'Failed to check payment status');
                resetConfirmButton(button, originalText);
            }
        } catch (error) {
            console.error('STK Query error:', error);
            
            let errorMessage = 'Network error checking payment';
            if (error.name === 'AbortError') {
                errorMessage = 'Request timed out';
            }
            
            showMessage('error', errorMessage);
            resetConfirmButton(button, originalText);
        }
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

    // Track payment start time
    window.paymentStartTime = Date.now();

    // Clean up interval when leaving page
    window.addEventListener('beforeunload', function() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
    });

    // FIXED: Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Payment page loaded');
        
        // Check if there's already a pending payment
        const urlParams = new URLSearchParams(window.location.search);
        const checkoutId = urlParams.get('checkout_request_id');
        
        if (checkoutId) {
            currentCheckoutId = checkoutId;
            document.getElementById('pay-button').style.display = 'none';
            document.getElementById('payment-processing-section').classList.remove('d-none');
            startStatusChecking();
        }
    });
</script>
</x-guest-layout>