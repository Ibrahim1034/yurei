<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Membership Activation</h4>
                            <p class="text-muted">Complete your registration via M-Pesa</p>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Name:</span>
                                <span class="fw-bold small">{{ $user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Phone:</span>
                                <span class="fw-bold small">{{ $user->phone_number }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Membership Fee:</span>
                                <span class="badge bg-primary fs-6">KES {{ number_format($amount ?? 1, 2) }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" id="pay-button" onclick="initiateSTKPush()">
                                <i class="fas fa-mobile-alt me-2"></i> Pay Now
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none small fw-bold">Already paid? Login here</a>
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

            // 1. LOADING STATE (The "Processing Payment" blade logic)
            Swal.fire({
                title: 'Initiating M-Pesa...',
                text: 'Please wait as we connect to Safaricom...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
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
                    
                    // 2. PROMPT SENT STATE
                    Swal.fire({
                        title: 'Prompt Sent!',
                        html: `Please enter your M-Pesa PIN on <strong>{{ $user->phone_number }}</strong>.`,
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        footer: '<div class="d-flex align-items-center"><div class="spinner-border spinner-border-sm text-primary me-2"></div><span>Waiting for payment...</span></div>'
                    });

                    startStatusChecking();
                } else {
                    isProcessing = false;
                    Swal.fire('Error', data.error || 'Failed to initiate.', 'error');
                }
            } catch (error) {
                isProcessing = false;
                Swal.fire('Connection Error', 'Check your internet and try again.', 'error');
            }
        }

        function startStatusChecking() {
            if (statusCheckInterval) clearInterval(statusCheckInterval);
            statusCheckInterval = setInterval(checkPaymentStatus, 4000);
        }

        async function checkPaymentStatus() {
            if (!currentCheckoutId) return;

            try {
                // Using your existing status route
                const response = await fetch(`/payment/status?checkout_request_id=${currentCheckoutId}`);
                const data = await response.json();

                if (data.success) {
                    if (data.status === 'completed') {
                        clearInterval(statusCheckInterval);
                        
                        // 3. SUCCESS STATE (The "Registration Complete" blade logic)
                        Swal.fire({
                            title: '🎉 Registration Complete!',
                            html: `
                                <div class="text-start small mt-3 p-3 bg-light rounded">
                                    <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                                    <p class="mb-1"><strong>Status:</strong> Account Active</p>
                                    <p class="mb-0"><strong>Receipt:</strong> Check your M-Pesa SMS</p>
                                </div>
                                <p class="mt-3">You are now officially a member of YUREI.</p>
                            `,
                            icon: 'success',
                            confirmButtonText: 'Proceed to Login',
                            confirmButtonColor: '#198754',
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.href = '{{ route("login") }}';
                        });

                    } else if (data.status === 'failed') {
                        clearInterval(statusCheckInterval);
                        isProcessing = false;
                        Swal.fire({
                            title: 'Payment Failed',
                            text: data.result_desc || 'Transaction declined.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

        window.addEventListener('beforeunload', () => clearInterval(statusCheckInterval));
    </script>
</x-guest-layout>