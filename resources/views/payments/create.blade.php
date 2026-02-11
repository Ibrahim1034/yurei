<x-guest-layout>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .payment-container {
            max-width: 550px;
            margin: 0 auto;
        }
        
        .payment-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            z-index: 1;
        }
        
        .header-icon i {
            font-size: 40px;
            color: #ffffff;
        }
        
        .card-header-gradient h4 {
            color: #ffffff;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .card-header-gradient p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        
        .card-body-content {
            padding: 40px 30px;
        }
        
        .membership-details-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .details-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .details-title i {
            color: #667eea;
        }
        
        .detail-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-icon {
            width: 40px;
            height: 40px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .detail-icon i {
            color: #667eea;
            font-size: 18px;
        }
        
        .detail-label {
            font-size: 12px;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            font-size: 15px;
            color: #2d3748;
            font-weight: 600;
            margin-top: 2px;
        }
        
        .membership-number {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            letter-spacing: 1px;
            color: #667eea;
        }
        
        .amount-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .amount-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.2), transparent);
        }
        
        .amount-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            position: relative;
        }
        
        .amount-value {
            color: #ffffff;
            font-size: 42px;
            font-weight: 800;
            margin: 0;
            position: relative;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .amount-currency {
            font-size: 24px;
            font-weight: 600;
            margin-right: 5px;
        }
        
        .pay-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .pay-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .pay-button:hover::before {
            left: 100%;
        }
        
        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        
        .pay-button:active {
            transform: translateY(0);
        }
        
        .pay-button i {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .phone-info {
            background: #f7fafc;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
            border: 1px dashed #cbd5e0;
        }
        
        .phone-info-text {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }
        
        .phone-number {
            color: #667eea;
            font-weight: 700;
            font-family: monospace;
        }
        
        .login-link-container {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-link {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .login-link:hover {
            color: #764ba2;
            gap: 12px;
        }
        
        .login-link i {
            font-size: 14px;
        }
        
        /* SweetAlert Custom Styles */
        .swal2-popup {
            border-radius: 20px;
            font-family: 'Inter', sans-serif;
        }
        
        .swal2-title {
            font-weight: 700;
            color: #2d3748;
        }
        
        .swal2-confirm {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 15px;
        }
        
        .swal2-deny {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 15px;
        }
        
        .swal2-cancel {
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 15px;
        }
        
        /* Spinner Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        
        .spinner-border-sm {
            width: 0.875rem;
            height: 0.875rem;
            border-width: 2px;
        }
    </style>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="payment-container">
                    <div class="payment-card">
                        <!-- Header -->
                        <div class="card-header-gradient">
                            <div class="header-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4>Complete Your Registration</h4>
                            <p>Secure payment to activate your membership</p>
                        </div>

                        <!-- Body -->
                        <div class="card-body-content">
                            <!-- Membership Details -->
                            <div class="membership-details-card">
                                <div class="details-title">
                                    <i class="fas fa-user-circle"></i>
                                    Membership Details
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="detail-label">Member Name</div>
                                        <div class="detail-value">{{ $user->name }}</div>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="detail-label">Phone Number</div>
                                        <div class="detail-value">{{ $user->phone_number }}</div>
                                    </div>
                                </div>
                                
                                @if($user->membership_number ?? false)
                                <div class="detail-row">
                                    <div class="detail-icon">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="detail-label">Membership Number</div>
                                        <div class="detail-value membership-number">{{ $user->membership_number }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Amount Card -->
                            <div class="amount-card">
                                <div class="amount-label">Membership Fee</div>
                                <div class="amount-value">
                                    <span class="amount-currency">KES</span>{{ number_format($amount ?? 1, 2) }}
                                </div>
                            </div>

                            <!-- Pay Button -->
                            <button type="button" class="pay-button" id="pay-button" onclick="initiateSTKPush()">
                                <i class="fas fa-mobile-alt"></i>Pay with M-Pesa
                            </button>

                            <!-- Phone Info -->
                            <div class="phone-info">
                                <p class="phone-info-text">
                                    <i class="fas fa-info-circle" style="color: #667eea;"></i>
                                    M-Pesa prompt will be sent to <span class="phone-number">{{ $user->phone_number }}</span>
                                </p>
                            </div>

                            <!-- Login Link -->
                            <div class="login-link-container">
                                <a href="{{ route('login') }}" class="login-link">
                                    Already paid? Login here
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
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
                    title: '<div style="color: #2d3748; font-weight: 700;">Initiating Payment</div>',
                    html: `
                        <div style="padding: 20px;">
                            <div style="margin-bottom: 20px;">
                                <i class="fas fa-sync fa-spin" style="font-size: 48px; color: #667eea;"></i>
                            </div>
                            <p style="color: #4a5568; font-size: 16px; margin: 0;">Connecting to M-Pesa...</p>
                            <p style="color: #718096; font-size: 14px; margin-top: 8px;">Please wait a moment</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    background: '#ffffff',
                    backdrop: 'rgba(102, 126, 234, 0.2)',
                    customClass: {
                        popup: 'swal2-popup'
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
                        title: '<div style="font-size: 28px; font-weight: 700; color: #2d3748;">📱 M-Pesa Prompt Sent!</div>',
                        html: `
                            <div style="text-align: left; padding: 10px;">
                                <!-- Success Header -->
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 16px; margin-bottom: 20px; text-align: center; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                                    <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 12px; animation: scaleIn 0.5s ease-out;"></i>
                                    <p style="margin: 0; font-size: 16px; font-weight: 600;">
                                        Prompt sent to <strong style="font-family: monospace; font-size: 17px;">${formatPhoneNumber(userData.phone)}</strong>
                                    </p>
                                </div>
                                
                                <!-- Payment Details -->
                                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; border: 2px solid #bae6fd;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                        <span style="color: #64748b; font-weight: 600; font-size: 14px;">
                                            <i class="fas fa-money-bill-wave" style="color: #667eea; margin-right: 8px;"></i>Amount
                                        </span>
                                        <span style="font-weight: 800; color: #10b981; font-size: 24px;">
                                            KES ${formatNumber(userData.amount)}
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: #64748b; font-weight: 600; font-size: 14px;">
                                            <i class="fas fa-hashtag" style="color: #667eea; margin-right: 8px;"></i>Reference
                                        </span>
                                        <span style="font-family: monospace; background: #ffffff; padding: 6px 12px; border-radius: 8px; font-weight: 700; color: #2d3748; border: 1px solid #cbd5e0;">
                                            ${currentCheckoutId.slice(-8)}
                                        </span>
                                    </div>
                                </div>

                                <!-- Instructions -->
                                <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 18px; border-radius: 12px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);">
                                    <p style="margin-bottom: 12px; color: #92400e; font-weight: 700; font-size: 15px;">
                                        <i class="fas fa-hourglass-half" style="margin-right: 8px;"></i>Waiting for payment...
                                    </p>
                                    <div style="color: #78350f; font-size: 14px; line-height: 1.8;">
                                        <div style="margin-bottom: 8px;">
                                            <i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>
                                            <strong>Step 1:</strong> Check your phone for M-Pesa prompt
                                        </div>
                                        <div style="margin-bottom: 8px;">
                                            <i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>
                                            <strong>Step 2:</strong> Enter your M-Pesa PIN
                                        </div>
                                        <div>
                                            <i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i>
                                            <strong>Step 3:</strong> Wait for confirmation
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                @keyframes scaleIn {
                                    from { transform: scale(0); opacity: 0; }
                                    to { transform: scale(1); opacity: 1; }
                                }
                            </style>
                        `,
                        icon: null,
                        showConfirmButton: false,
                        showDenyButton: true,
                        denyButtonText: '<i class="fas fa-sync" style="margin-right: 8px;"></i>Check Status',
                        denyButtonColor: '#f59e0b',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fas fa-times" style="margin-right: 8px;"></i>Cancel',
                        cancelButtonColor: '#6b7280',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        width: '600px',
                        backdrop: 'rgba(102, 126, 234, 0.2)',
                        customClass: {
                            popup: 'swal2-popup',
                            denyButton: 'swal2-deny',
                            cancelButton: 'swal2-cancel'
                        },
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
                                title: '<div style="color: #2d3748; font-weight: 700;">Payment Cancelled</div>',
                                html: '<p style="color: #4a5568; margin: 0;">You have cancelled the payment process.</p>',
                                icon: 'info',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#667eea',
                                backdrop: 'rgba(102, 126, 234, 0.2)'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                    
                    button.style.display = 'none';
                } else {
                    Swal.fire({
                        title: '<div style="color: #2d3748; font-weight: 700;">Payment Initiation Failed</div>',
                        html: `<p style="color: #4a5568;">${data.error || 'Failed to initiate payment. Please try again.'}</p>`,
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#667eea',
                        backdrop: 'rgba(239, 68, 68, 0.2)'
                    });
                    resetButton(button, originalText);
                }
            } catch (error) {
                console.error('STK Push error:', error);
                Swal.fire({
                    title: '<div style="color: #2d3748; font-weight: 700;">Connection Error</div>',
                    html: '<p style="color: #4a5568;">Failed to connect to M-Pesa. Please check your internet connection.</p>',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#667eea',
                    backdrop: 'rgba(239, 68, 68, 0.2)'
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
                            title: '<div style="color: #2d3748; font-weight: 700;">⏱️ Payment Timeout</div>',
                            html: '<p style="color: #4a5568;">The payment request has expired.<br><br>Would you like to try again?</p>',
                            icon: 'warning',
                            confirmButtonText: 'Yes, Try Again',
                            confirmButtonColor: '#667eea',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel',
                            backdrop: 'rgba(251, 191, 36, 0.2)'
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
                        title: '<div style="font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🎉 Payment Successful!</div>',
                        html: `
                            <div style="text-align: left; padding: 10px;">
                                <!-- Success Header -->
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 35px 25px; border-radius: 20px; margin-bottom: 25px; text-align: center; box-shadow: 0 20px 60px rgba(16, 185, 129, 0.4); position: relative; overflow: hidden;">
                                    <div style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                                    <div style="position: absolute; bottom: -30px; left: -30px; width: 180px; height: 180px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                                    <i class="fas fa-check-circle" style="font-size: 72px; margin-bottom: 18px; position: relative; z-index: 1; animation: successPop 0.6s ease-out;"></i>
                                    <h2 style="color: white; margin-bottom: 12px; font-size: 28px; position: relative; z-index: 1;">Welcome to YUREI!</h2>
                                    <p style="color: rgba(255,255,255,0.95); margin-bottom: 0; font-size: 16px; position: relative; z-index: 1;">Your membership is now active</p>
                                </div>
                                
                                <!-- Membership Card -->
                                <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 2px solid #e2e8f0; border-radius: 20px; padding: 28px; margin-bottom: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                                    <h4 style="color: #1e293b; margin-bottom: 25px; font-size: 20px; font-weight: 700; border-bottom: 3px solid #667eea; padding-bottom: 12px; display: inline-block;">
                                        <i class="fas fa-id-card" style="margin-right: 10px; color: #667eea;"></i>Membership Details
                                    </h4>
                                    
                                    <div style="display: grid; gap: 20px;">
                                        <div style="display: flex; align-items: center; background: #f8fafc; padding: 16px; border-radius: 12px;">
                                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                                <i class="fas fa-user" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Member Name</div>
                                                <div style="font-weight: 700; font-size: 17px; color: #1e293b;">${userData.name}</div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; align-items: center; background: #f0f9ff; padding: 16px; border-radius: 12px; border: 2px solid #bae6fd;">
                                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                                <i class="fas fa-id-badge" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 11px; color: #0369a1; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Membership Number</div>
                                                <div style="font-weight: 800; font-size: 20px; color: #0c4a6e; letter-spacing: 1.5px; font-family: monospace;">
                                                    ${data.membership_number || userData.membership_number}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; align-items: center; background: #f8fafc; padding: 16px; border-radius: 12px;">
                                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                                <i class="fas fa-phone" style="color: white; font-size: 20px;"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone Number</div>
                                                <div style="font-weight: 600; font-size: 16px; color: #1e293b; font-family: monospace;">${formatPhoneNumber(userData.phone)}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Details -->
                                <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 16px; padding: 22px; margin-bottom: 20px; border: 2px solid #6ee7b7;">
                                    <h5 style="color: #065f46; margin-bottom: 18px; font-size: 17px; font-weight: 700;">
                                        <i class="fas fa-receipt" style="margin-right: 8px;"></i>Payment Information
                                    </h5>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div style="background: white; padding: 14px; border-radius: 10px;">
                                            <div style="font-size: 11px; color: #047857; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Amount Paid</div>
                                            <div style="font-weight: 800; color: #10b981; font-size: 22px;">
                                                KES ${formatNumber(userData.amount)}
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 14px; border-radius: 10px;">
                                            <div style="font-size: 11px; color: #047857; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">Payment Date</div>
                                            <div style="font-weight: 600; font-size: 13px; color: #065f46;">${new Date().toLocaleDateString('en-KE', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                                        </div>
                                        ${data.receipt_number ? `
                                        <div style="grid-column: span 2; background: white; padding: 14px; border-radius: 10px;">
                                            <div style="font-size: 11px; color: #047857; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">M-Pesa Receipt Number</div>
                                            <div style="font-weight: 700; font-family: monospace; background: #f0fdf4; padding: 8px 14px; border-radius: 8px; display: inline-block; font-size: 15px; color: #065f46; border: 1px solid #86efac;">
                                                ${data.receipt_number}
                                            </div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <!-- Welcome Message -->
                                <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 14px; padding: 20px; text-align: center; border: 2px solid #93c5fd;">
                                    <p style="margin-bottom: 0; color: #1e40af; font-weight: 600; font-size: 15px;">
                                        <i class="fas fa-star" style="color: #fbbf24; margin-right: 8px;"></i> 
                                        Thank you for joining our community! Access all member features by logging in.
                                        <i class="fas fa-star" style="color: #fbbf24; margin-left: 8px;"></i>
                                    </p>
                                </div>
                            </div>
                            <style>
                                @keyframes successPop {
                                    0% { transform: scale(0); opacity: 0; }
                                    50% { transform: scale(1.1); }
                                    100% { transform: scale(1); opacity: 1; }
                                }
                            </style>
                        `,
                        icon: null,
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fas fa-sign-in-alt" style="margin-right: 10px;"></i>Proceed to Login',
                        confirmButtonColor: '#10b981',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        backdrop: 'rgba(16, 185, 129, 0.25)',
                        width: '650px',
                        customClass: {
                            popup: 'swal2-popup',
                            confirmButton: 'swal2-confirm'
                        },
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
                    let errorColor = '#ef4444';
                    
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
                        title: `<div style="color: #2d3748; font-weight: 700;">${errorTitle}</div>`,
                        html: `
                            <div style="padding: 20px;">
                                <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; border: 2px solid #fca5a5;">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #dc2626; margin-bottom: 15px;"></i>
                                    <p style="color: #991b1b; font-size: 16px; margin: 0; font-weight: 600;">${errorMessage}</p>
                                </div>
                            </div>
                        `,
                        icon: null,
                        confirmButtonText: '<i class="fas fa-redo" style="margin-right: 8px;"></i>Try Again',
                        confirmButtonColor: '#667eea',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        cancelButtonColor: '#6b7280',
                        backdrop: 'rgba(239, 68, 68, 0.2)',
                        customClass: {
                            popup: 'swal2-popup'
                        }
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
                            <div style="text-align: left; padding: 10px;">
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 16px; margin-bottom: 20px; text-align: center; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                                    <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 12px;"></i>
                                    <p style="margin: 0; font-size: 16px; font-weight: 600;">
                                        Prompt sent to <strong style="font-family: monospace; font-size: 17px;">${formatPhoneNumber(userData.phone)}</strong>
                                    </p>
                                </div>
                                
                                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; border: 2px solid #bae6fd;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                        <span style="color: #64748b; font-weight: 600; font-size: 14px;">
                                            <i class="fas fa-money-bill-wave" style="color: #667eea; margin-right: 8px;"></i>Amount
                                        </span>
                                        <span style="font-weight: 800; color: #10b981; font-size: 24px;">
                                            KES ${formatNumber(userData.amount)}
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: #64748b; font-weight: 600; font-size: 14px;">
                                            <i class="fas fa-clock" style="color: #667eea; margin-right: 8px;"></i>Time Elapsed
                                        </span>
                                        <span style="font-family: monospace; background: #ffffff; padding: 6px 12px; border-radius: 8px; font-weight: 700; color: #f59e0b; border: 1px solid #fbbf24;">
                                            ⏱️ ${elapsedSeconds}s
                                        </span>
                                    </div>
                                </div>

                                <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 18px; border-radius: 12px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);">
                                    <p style="margin-bottom: 8px; color: #92400e; font-weight: 700; font-size: 15px;">
                                        <i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Waiting for your response...
                                    </p>
                                    <p style="margin-bottom: 0; font-size: 14px; color: #78350f;">
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
                    title: '<div style="color: #2d3748; font-weight: 700;">Error</div>',
                    html: '<p style="color: #4a5568;">No active payment session found.</p>',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            Swal.fire({
                title: '<div style="color: #2d3748; font-weight: 700;">Checking Payment Status</div>',
                html: `
                    <div style="padding: 20px;">
                        <i class="fas fa-sync fa-spin" style="font-size: 48px; color: #667eea;"></i>
                        <p style="color: #4a5568; margin-top: 15px;">Verifying with M-Pesa...</p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                backdrop: 'rgba(102, 126, 234, 0.2)'
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
                            title: '<div style="color: #2d3748; font-weight: 700;">Payment Not Found</div>',
                            html: '<p style="color: #4a5568;">We could not find a successful payment. Please try again.</p>',
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#667eea',
                            backdrop: 'rgba(251, 191, 36, 0.2)'
                        });
                    }
                } else {
                    Swal.fire({
                        title: '<div style="color: #2d3748; font-weight: 700;">Status Check Failed</div>',
                        html: `<p style="color: #4a5568;">${data.error || 'Failed to check payment status'}</p>`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#667eea'
                    });
                }
            } catch (error) {
                console.error('STK Query error:', error);
                Swal.fire({
                    title: '<div style="color: #2d3748; font-weight: 700;">Connection Error</div>',
                    html: '<p style="color: #4a5568;">Failed to check payment status. Please try again.</p>',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#667eea'
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
                    title: '<div style="color: #2d3748; font-weight: 700;">Processing Payment</div>',
                    html: `
                        <div style="padding: 20px;">
                            <i class="fas fa-sync fa-spin" style="font-size: 48px; color: #667eea;"></i>
                            <p style="color: #4a5568; margin-top: 15px;">Checking existing payment session...</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    backdrop: 'rgba(102, 126, 234, 0.2)',
                    didOpen: () => {
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
</x-guest-layout>