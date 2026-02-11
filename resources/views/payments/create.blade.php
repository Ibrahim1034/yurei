<x-guest-layout>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
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
                                    @if($user->membership_number ?? false)
                                    <div class="col-4 text-muted">Membership No:</div>
                                    <div class="col-8">{{ $user->membership_number }}</div>
                                    @endif
                                </div>

                                <div class="alert alert-info mb-0">
                                    <strong>Membership Fee:</strong> KES {{ number_format($amount ?? 1, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Pay Button -->
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary btn-lg" id="pay-button" onclick="initiateSTKPush()">
                                <i class="fas fa-mobile-alt me-2"></i>Pay via M-Pesa
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                You will receive an M-Pesa prompt on: <strong>{{ $user->phone_number }}</strong>
                            </small>
                        </div>

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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // User data for modals
        const userData = {
            id: '{{ $user->id }}',
            name: '{{ $user->name }}',
            phone: '{{ $user->phone_number }}',
            amount: '{{ $amount ?? 1 }}',
            membership_number: '{{ $user->membership_number ?? 'Pending' }}'
        };

        let currentCheckoutId = null;
        let statusCheckInterval = null;
        let paymentStartTime = null;

        // Initialize STK Push
        async function initiateSTKPush() {
            const button = document.getElementById('pay-button');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Initiating...';
            button.disabled = true;

            try {
                // Show loading modal
                Swal.fire({
                    title: 'Initiating Payment',
                    html: 'Connecting to M-Pesa...<br><br>Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch('{{ route("mpesa.stk.push") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userData.id,
                        amount: userData.amount,
                        phonenumber: userData.phone
                    })
                });

                const data = await response.json();

                if (data.success) {
                    currentCheckoutId = data.checkout_request_id;
                    paymentStartTime = Date.now();
                    
                    // Show STK Push Sent modal
                    Swal.fire({
                        title: '📱 M-Pesa Prompt Sent!',
                        html: `
                            <div style="text-align: left;">
                                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <p style="margin-bottom: 5px; color: #0d47a1;">
                                        <i class="fas fa-check-circle"></i> 
                                        Prompt sent to <strong>${formatPhoneNumber(userData.phone)}</strong>
                                    </p>
                                </div>
                                
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="color: #6c757d;">Amount:</span>
                                        <span style="font-weight: bold; color: #28a745; font-size: 1.2em;">
                                            KES ${formatNumber(userData.amount)}
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: #6c757d;">Reference:</span>
                                        <span style="font-family: monospace; background: #e9ecef; padding: 3px 8px; border-radius: 4px;">
                                            ${currentCheckoutId.slice(-8)}
                                        </span>
                                    </div>
                                </div>

                                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; border-radius: 5px;">
                                    <p style="margin-bottom: 5px; color: #856404;">
                                        <strong>⏱️ Waiting for payment...</strong>
                                    </p>
                                    <p style="margin-bottom: 0; font-size: 14px; color: #856404;">
                                        1. Enter your M-Pesa PIN when prompted<br>
                                        2. Wait for confirmation message<br>
                                        3. Click "Check Status" to verify
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        showConfirmButton: false,
                        showDenyButton: true,
                        denyButtonText: '🔄 Check Status',
                        denyButtonColor: '#ffc107',
                        showCancelButton: true,
                        cancelButtonText: '❌ Cancel',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            startStatusChecking();
                        }
                    }).then((result) => {
                        if (result.isDenied) {
                            confirmPayment();
                        }
                        if (result.isDismissed) {
                            clearInterval(statusCheckInterval);
                            Swal.fire({
                                title: 'Payment Cancelled',
                                text: 'You have cancelled the payment process.',
                                icon: 'info',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#007bff'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                    
                    button.style.display = 'none';
                } else {
                    Swal.fire({
                        title: 'Payment Initiation Failed',
                        text: data.error || 'Failed to initiate payment. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#007bff'
                    });
                    resetButton(button, originalText);
                }
            } catch (error) {
                console.error('STK Push error:', error);
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Failed to connect to M-Pesa. Please check your internet connection.',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#007bff'
                });
                resetButton(button, originalText);
            }
        }

        // Start checking payment status
        function startStatusChecking() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
            
            // Check immediately
            checkPaymentStatus();
            
            // Then check every 5 seconds
            statusCheckInterval = setInterval(checkPaymentStatus, 5000);

            // Auto-cancel after 60 seconds
            setTimeout(() => {
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                    if (Swal.isVisible()) {
                        Swal.fire({
                            title: '⏱️ Payment Timeout',
                            html: 'The payment request has expired.<br><br>Would you like to try again?',
                            icon: 'warning',
                            confirmButtonText: 'Yes, Try Again',
                            confirmButtonColor: '#007bff',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                }
            }, 60000);
        }

        // Check payment status
        async function checkPaymentStatus() {
            if (!currentCheckoutId) return;

            try {
                const response = await fetch('/v1/status?checkout_request_id=' + currentCheckoutId, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();

                if (data.success) {
                    handlePaymentStatus(data);
                }
            } catch (error) {
                console.error('Status check error:', error);
            }
        }

        // Handle payment status response
        function handlePaymentStatus(data) {
            const status = data.status;
            const resultDesc = data.result_desc || '';
            const elapsedSeconds = Math.floor((Date.now() - paymentStartTime) / 1000);

            switch(status) {
                case 'completed':
                    // Payment successful - Show success modal with confirm button
                    clearInterval(statusCheckInterval);
                    
                    Swal.fire({
                        title: '🎉 Payment Successful!',
                        html: `
                            <div style="text-align: left;">
                                <!-- Success Header -->
                                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 25px; border-radius: 15px; margin-bottom: 20px; text-align: center;">
                                    <i class="fas fa-check-circle" style="font-size: 64px; margin-bottom: 15px;"></i>
                                    <h2 style="color: white; margin-bottom: 10px;">Welcome to YUREI!</h2>
                                    <p style="color: white; opacity: 0.95; margin-bottom: 0;">Your membership is now active</p>
                                </div>
                                
                                <!-- Membership Card Style Details -->
                                <div style="background: white; border: 1px solid #e9ecef; border-radius: 15px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                    <h4 style="color: #333; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px;">
                                        <i class="fas fa-id-card me-2"></i>Membership Details
                                    </h4>
                                    
                                    <div style="display: grid; gap: 15px;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 40px; color: #6c757d;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 12px; color: #6c757d;">Member Name</div>
                                                <div style="font-weight: 600; font-size: 16px;">${userData.name}</div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 40px; color: #6c757d;">
                                                <i class="fas fa-id-badge"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 12px; color: #6c757d;">Membership Number</div>
                                                <div style="font-weight: 700; font-size: 18px; color: #007bff; letter-spacing: 1px;">
                                                    ${data.membership_number || userData.membership_number}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 40px; color: #6c757d;">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 12px; color: #6c757d;">Phone Number</div>
                                                <div>${formatPhoneNumber(userData.phone)}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Details -->
                                <div style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-bottom: 15px;">
                                    <h5 style="color: #333; margin-bottom: 15px;">Payment Information</h5>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                        <div>
                                            <div style="font-size: 12px; color: #6c757d;">Amount Paid</div>
                                            <div style="font-weight: 700; color: #28a745; font-size: 20px;">
                                                KES ${formatNumber(userData.amount)}
                                            </div>
                                        </div>
                                        <div>
                                            <div style="font-size: 12px; color: #6c757d;">Payment Date</div>
                                            <div style="font-weight: 600;">${new Date().toLocaleDateString('en-KE', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                                        </div>
                                        ${data.receipt_number ? `
                                        <div style="grid-column: span 2;">
                                            <div style="font-size: 12px; color: #6c757d;">M-Pesa Receipt Number</div>
                                            <div style="font-weight: 600; font-family: monospace; background: #e9ecef; padding: 5px 10px; border-radius: 5px; display: inline-block;">
                                                ${data.receipt_number}
                                            </div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <!-- Welcome Message -->
                                <div style="background: #e8f4fd; border-radius: 10px; padding: 15px; text-align: center;">
                                    <p style="margin-bottom: 0; color: #0056b3;">
                                        <i class="fas fa-star"></i> 
                                        Thank you for joining our community! Access all member features by logging in.
                                        <i class="fas fa-star"></i>
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonText: '🔐 Proceed to Login',
                        confirmButtonColor: '#28a745',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        backdrop: `rgba(40,167,69,0.2)`,
                        width: '600px',
                        didOpen: () => {
                            // Update membership number if received
                            if (data.membership_number) {
                                userData.membership_number = data.membership_number;
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("login") }}';
                        }
                    });
                    break;

                case 'failed':
                    clearInterval(statusCheckInterval);
                    
                    let errorTitle = 'Payment Failed';
                    let errorIcon = 'error';
                    let errorMessage = resultDesc || 'Your payment could not be processed.';
                    
                    if (resultDesc.toLowerCase().includes('cancelled')) {
                        errorTitle = '❌ Payment Cancelled';
                        errorMessage = 'You cancelled the payment on your phone.';
                    } else if (resultDesc.toLowerCase().includes('timeout')) {
                        errorTitle = '⏱️ Payment Timeout';
                        errorMessage = 'The payment request expired.';
                    } else if (resultDesc.toLowerCase().includes('insufficient')) {
                        errorTitle = '💳 Insufficient Funds';
                        errorMessage = 'You do not have enough funds in your M-Pesa account.';
                    }

                    Swal.fire({
                        title: errorTitle,
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#007bff',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                    break;

                case 'pending':
                    // Update the modal with timer
                    if (Swal.isVisible()) {
                        const timerHtml = `
                            <div style="text-align: left;">
                                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <p style="margin-bottom: 5px; color: #0d47a1;">
                                        <i class="fas fa-check-circle"></i> 
                                        Prompt sent to <strong>${formatPhoneNumber(userData.phone)}</strong>
                                    </p>
                                </div>
                                
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="color: #6c757d;">Amount:</span>
                                        <span style="font-weight: bold; color: #28a745; font-size: 1.2em;">
                                            KES ${formatNumber(userData.amount)}
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: #6c757d;">Time Elapsed:</span>
                                        <span style="font-family: monospace; background: #e9ecef; padding: 3px 8px; border-radius: 4px;">
                                            ⏱️ ${elapsedSeconds}s
                                        </span>
                                    </div>
                                </div>

                                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; border-radius: 5px;">
                                    <p style="margin-bottom: 5px; color: #856404;">
                                        <strong>⏳ Waiting for your response...</strong>
                                    </p>
                                    <p style="margin-bottom: 0; font-size: 14px; color: #856404;">
                                        Please check your phone and enter your M-Pesa PIN
                                    </p>
                                </div>
                            </div>
                        `;
                        
                        Swal.update({
                            html: timerHtml
                        });
                    }
                    break;
            }
        }

        // Confirm payment manually
        async function confirmPayment() {
            if (!currentCheckoutId) {
                Swal.fire({
                    title: 'Error',
                    text: 'No active payment session found.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Checking Payment Status',
                html: 'Verifying with M-Pesa...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch('{{ route("mpesa.stk.query") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        checkout_request_id: currentCheckoutId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    if (data.result.ResultCode == '0') {
                        // Payment successful
                        handlePaymentStatus({
                            status: 'completed',
                            receipt_number: data.result.MpesaReceiptNumber,
                            membership_number: data.membership_number
                        });
                    } else {
                        // Payment failed
                        Swal.fire({
                            title: 'Payment Not Found',
                            text: 'We could not find a successful payment. Please try again.',
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#007bff'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Status Check Failed',
                        text: data.error || 'Failed to check payment status',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('STK Query error:', error);
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Failed to check payment status. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

        // Utility Functions
        function formatNumber(number) {
            return parseFloat(number).toLocaleString('en-KE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatPhoneNumber(phone) {
            if (!phone) return '';
            // Format: 07XX XXX XXX
            const cleaned = phone.replace(/\D/g, '');
            if (cleaned.length === 10) {
                return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
            } else if (cleaned.length === 12) {
                return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{3})/, '$1 $2 $3 $4');
            }
            return phone;
        }

        function resetButton(button, originalText) {
            button.innerHTML = originalText;
            button.disabled = false;
        }

        // Check for existing payment session on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const checkoutId = urlParams.get('checkout_request_id');
            
            if (checkoutId) {
                currentCheckoutId = checkoutId;
                paymentStartTime = Date.now();
                document.getElementById('pay-button').style.display = 'none';
                
                // Show processing modal
                Swal.fire({
                    title: 'Processing Payment',
                    html: 'Checking existing payment session...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        startStatusChecking();
                    }
                });
            }
        });

        // Clean up interval when leaving page
        window.addEventListener('beforeunload', function() {
            if (statusCheckInterval) {
                clearInterval(statusCheckInterval);
            }
        });
    </script>

    <!-- Optional: Add Font Awesome if not already included -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-guest-layout>