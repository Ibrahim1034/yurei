<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Membership Activation</h4>
                            <p class="text-muted">Pay via M-Pesa to complete registration</p>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Member:</span>
                                <span class="fw-bold">{{ $user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Phone:</span>
                                <span class="fw-bold">{{ $user->phone_number }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Amount:</span>
                                <span class="badge bg-primary fs-6">KES {{ number_format($amount ?? 1, 2) }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg shadow-sm" id="pay-button" onclick="initiateSTKPush()">
                                <i class="fas fa-mobile-alt me-2"></i> Pay via M-Pesa
                            </button>
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
        let timeLeft = 60; // 60 seconds timer
        let timerInterval;

        async function initiateSTKPush() {
            // 1. Initial Processing Modal
            Swal.fire({
                title: 'Sending Prompt...',
                text: 'Connecting to Safaricom...',
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
                    startWaitingPeriod();
                } else {
                    Swal.fire('Error', data.error || 'Failed to send prompt', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network connection failed', 'error');
            }
        }

        // 2. The 60-Second Wait Logic
        function startWaitingPeriod() {
            timeLeft = 60;
            
            Swal.fire({
                title: 'Prompt Sent!',
                html: `Please check your phone and enter your PIN.<br><br>Checking status in <b>60</b> seconds...`,
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 60000, // 60 seconds
                timerProgressBar: true,
                didOpen: () => {
                    const b = Swal.getHtmlContainer().querySelector('b');
                    timerInterval = setInterval(() => {
                        timeLeft--;
                        b.textContent = timeLeft;
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                // When 60s is up, automatically trigger confirmation
                if (result.dismiss === Swal.DismissReason.timer) {
                    confirmPaymentAuto();
                }
            });
        }

        // 3. Automatic Confirmation After 60s
        async function confirmPaymentAuto() {
            Swal.fire({
                title: 'Confirming Payment...',
                text: 'Verifying your transaction with Safaricom...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                // Using your check status route
                const response = await fetch(`/payment/status?checkout_request_id=${currentCheckoutId}`);
                const data = await response.json();

                if (data.success && data.status === 'completed') {
                    // 4. FINAL SUCCESS MESSAGE & REDIRECT
                    Swal.fire({
                        title: '🎉 Registration Complete!',
                        html: `Welcome to YUREI!<br>Your membership number: <b>{{ $user->membership_number }}</b><br>Amount: <b>KES {{ number_format($amount ?? 1, 2) }}</b>`,
                        icon: 'success',
                        confirmButtonText: 'Proceed to Dashboard',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '{{ route("login") }}'; // Redirect to dashboard/login
                    });
                } else {
                    // Fail state
                    Swal.fire({
                        title: 'Payment Not Found',
                        text: 'We could not verify your payment. Did you enter the PIN?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Try Again',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) { initiateSTKPush(); }
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to verify payment status', 'error');
            }
        }
    </script>
</x-guest-layout>