<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-shield-alt text-primary fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Secure Registration</h4>
                            <p class="text-muted">Complete payment to activate your account</p>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Member Name:</span>
                                <span class="fw-bold small">{{ $user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Phone Number:</span>
                                <span class="fw-bold small">{{ $user->phone_number }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Due:</span>
                                <span class="badge bg-primary fs-6">KES {{ number_format($amount ?? 1, 2) }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" id="pay-button" onclick="initiateSTKPush()">
                                <i class="fas fa-mobile-alt me-2"></i> Pay via M-Pesa
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-1">Having trouble?</p>
                            <a href="{{ route('login') }}" class="text-decoration-none small fw-bold">
                                Already paid? Login here
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        let currentCheckoutId = null;
        let isProcessing = false;
        let statusCheckInterval = null;

        async function initiateSTKPush() {
            if (isProcessing) return;
            isProcessing = true;

            // 1. Show Loading State
            Swal.fire({
                title: 'Requesting Payment...',
                text: 'Please wait while we trigger the M-Pesa prompt on your phone.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
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
                    })
                });

                const data = await response.json();

                if (data.success) {
                    currentCheckoutId = data.checkout_request_id;
                    
                    // 2. Update UI to "Waiting for User PIN"
                    Swal.fire({
                        title: 'Prompt Sent!',
                        html: `Please check your phone (<strong>{{ $user->phone_number }}</strong>) and enter your M-Pesa PIN to authorize.`,
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        footer: '<div class="spinner-border spinner-border-sm text-primary me-2"></div> Listening for payment...'
                    });

                    startStatusChecking();
                } else {
                    isProcessing = false;
                    Swal.fire('Failed', data.error || 'Could not initiate STK push.', 'error');
                }
            } catch (error) {
                isProcessing = false;
                Swal.fire('Connection Error', 'Unable to reach payment gateway. Check your internet.', 'error');
            }
        }

        function startStatusChecking() {
            if (statusCheckInterval) clearInterval(statusCheckInterval);
            // Check every 4 seconds for the callback update in your DB
            statusCheckInterval = setInterval(checkPaymentStatus, 4000);
        }

        async function checkPaymentStatus() {
            if (!currentCheckoutId) return;

            try {
                // Ensure this route returns the payment status from your 'mpesa_transactions' table
                const response = await fetch(`/v1/status?checkout_request_id=${currentCheckoutId}`);
                const data = await response.json();

                if (data.success) {
                    if (data.status === 'completed') {
                        clearInterval(statusCheckInterval);
                        
                        Swal.fire({
                            title: 'Success!',
                            text: 'Payment received. Your account is now active!',
                            icon: 'success',
                            confirmButtonText: 'Go to Dashboard',
                            allowOutsideClick: false
                        }).then((result) => {
                            window.location.href = '{{ route("login") }}';
                        });

                    } else if (data.status === 'failed') {
                        clearInterval(statusCheckInterval);
                        isProcessing = false;
                        
                        Swal.fire({
                            title: 'Payment Failed',
                            text: data.result_desc || 'The transaction was cancelled or declined.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

        // Cleanup on page leave
        window.addEventListener('beforeunload', () => clearInterval(statusCheckInterval));
    </script>
</x-guest-layout>