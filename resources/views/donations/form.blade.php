
<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-heart me-2"></i>Make a Donation
            </h2>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="dashboard-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-heart-fill text-danger display-4"></i>
                            <h3 class="mt-3">Support Our Cause</h3>
                            <p class="text-muted">Your donation helps us empower more youth through education, health, and entrepreneurship programs.</p>
                        </div>

                        <form id="donation-form">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="donor_name" class="form-label">Your Name (Optional)</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="donor_name" 
                                       name="donor_name" 
                                       placeholder="Enter your name">
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">M-Pesa Phone Number *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="phone_number" 
                                       name="phone_number" 
                                       placeholder="2547XXXXXXXX" 
                                       required>
                                <div class="form-text">Enter your M-Pesa registered phone number</div>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="form-label">Donation Amount (KES) *</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="amount" 
                                       name="amount" 
                                       min="1" 
                                       max="100000" 
                                       placeholder="Enter amount" 
                                       required>
                               
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-danger btn-lg" id="donate-button" onclick="initiateDonation()">
                                    <i class="bi bi-heart me-1"></i>Donate Now
                                </button>
                            </div>
                        </form>

                        <!-- Payment Processing Section -->
                        <div id="payment-processing-section" class="text-center mt-4 d-none">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Processing...</span>
                            </div>
                            <p class="mt-2" id="status-message">Processing donation...</p>
                            
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

                    <!-- Impact Information -->
                    <div class="dashboard-card mt-4">
                        <h5><i class="bi bi-graph-up me-2"></i>How Your Donation Helps</h5>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="impact-stat">
                                    <h6 class="text-primary">KES 500</h6>
                                    <small>School supplies for one child</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="impact-stat">
                                    <h6 class="text-success">KES 1,000</h6>
                                    <small>Vocational training sponsorship</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="impact-stat">
                                    <h6 class="text-info">KES 5,000</h6>
                                    <small>Community health initiative</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentCheckoutId = null;
    let currentDonationId = null;
    let isProcessing = false;
    let statusCheckInterval = null;

    function initiateDonation() {
    if (isProcessing) return;
    
    isProcessing = true;
    const button = document.getElementById('donate-button');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
    button.disabled = true;

    hideAllMessages();

    // Get form data
    const donorName = document.getElementById('donor_name').value;
    const phoneNumber = document.getElementById('phone_number').value;
    const amount = document.getElementById('amount').value;

    // Client-side validation
    if (!phoneNumber) {
        showMessage('error', 'Please enter your M-Pesa phone number.');
        resetButton(button, originalText);
        return;
    }

    // Validate phone number format
    const phoneRegex = /^254[0-9]{9}$/;
    if (!phoneRegex.test(phoneNumber)) {
        showMessage('error', 'Please enter a valid M-Pesa number in format 2547XXXXXXXX');
        resetButton(button, originalText);
        return;
    }

    if (!amount || amount < 1 || amount > 100000) {
        showMessage('error', 'Please enter a valid donation amount between KES 10 and KES 100,000.');
        resetButton(button, originalText);
        return;
    }

    const donationData = {
        _token: '{{ csrf_token() }}',
        donor_name: donorName,
        phone_number: phoneNumber,
        amount: parseFloat(amount)
    };

    fetch('{{ route("donations.initiate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(donationData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.error || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            currentCheckoutId = data.checkout_request_id;
            currentDonationId = data.donation_id;
            
            document.getElementById('payment-processing-section').classList.remove('d-none');
            document.getElementById('status-message').textContent = 'M-Pesa prompt sent. Please check your phone.';
            startStatusChecking();
        } else {
            showMessage('error', data.error || 'Failed to initiate donation');
            resetButton(button, originalText);
        }
    })
    .catch(error => {
        console.error('Donation initiation error:', error);
        showMessage('error', 'Donation failed: ' + error.message);
        resetButton(button, originalText);
    });
}

    function startStatusChecking() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        
        checkDonationStatus();
        statusCheckInterval = setInterval(checkDonationStatus, 5000);
    }

    function checkDonationStatus() {
        if (!currentCheckoutId) return;
        
        fetch('{{ route("donations.status") }}?checkout_request_id=' + currentCheckoutId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                handleDonationStatus(data);
            } else {
                console.error('Status check failed:', data.error);
            }
        })
        .catch(error => {
            console.error('Status check error:', error);
        });
    }

    function handleDonationStatus(data) {
        const status = data.status;
        const resultDesc = data.result_desc || '';
        
        switch(status) {
            case 'completed':
                showMessage('success', 'Thank you! Your donation of KES ' + data.amount + ' was successful.');
                clearInterval(statusCheckInterval);
                
                // Store success message in sessionStorage for welcome page
                sessionStorage.setItem('donationSuccess', 'true');
                sessionStorage.setItem('donationMessage', 'Thank you for your generous donation of KES ' + data.amount + '!');
                sessionStorage.setItem('donationAmount', data.amount);
                
                // Redirect to welcome page after 3 seconds
                setTimeout(() => {
                    window.location.href = '{{ url("/") }}';
                }, 3000);
                break;
                
            case 'failed':
                if (resultDesc.toLowerCase().includes('cancelled')) {
                    showMessage('error', 'Donation cancelled by user');
                } else if (resultDesc.toLowerCase().includes('timeout')) {
                    showMessage('error', 'Donation timed out');
                } else {
                    showMessage('error', 'Donation failed: ' + resultDesc);
                }
                clearInterval(statusCheckInterval);
                resetProcessing();
                break;
                
            case 'pending':
                document.getElementById('status-message').textContent = 'Waiting for payment confirmation...';
                break;
                
            default:
                document.getElementById('status-message').textContent = 'Processing donation...';
                break;
        }
    }

    function confirmPayment() {
        if (!currentCheckoutId) {
            showMessage('error', 'No donation session found');
            return;
        }

        const button = document.getElementById('confirm-payment-btn');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Checking...';
        button.disabled = true;

        fetch('{{ route("donations.query") }}', {
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
                    showMessage('success', 'Thank you! Your donation was successful.');
                    
                    // Store success message in sessionStorage
                    sessionStorage.setItem('donationSuccess', 'true');
                    sessionStorage.setItem('donationMessage', 'Thank you for your generous donation!');
                    
                    // Redirect to welcome page
                    setTimeout(() => {
                        window.location.href = '{{ url("/") }}';
                    }, 2000);
                } else {
                    handleDonationStatus(data);
                }
            } else {
                showMessage('error', 'Failed to check donation: ' + data.error);
                resetConfirmButton(button, originalText);
            }
        })
        .catch(error => {
            console.error('Donation confirmation error:', error);
            showMessage('error', 'Network error checking donation');
            resetConfirmButton(button, originalText);
        });
    }

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
        const button = document.getElementById('donate-button');
        if (button) {
            button.innerHTML = '<i class="bi bi-heart me-1"></i>Donate Now';
            button.disabled = false;
        }
        document.getElementById('payment-processing-section').classList.add('d-none');
    }

    window.addEventListener('beforeunload', function() {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
    });
    </script>

    <style>
    .impact-stat {
        padding: 10px;
        border-radius: 8px;
        background: #f8f9fa;
    }
    .impact-stat h6 {
        margin-bottom: 5px;
        font-weight: 700;
    }
    .impact-stat small {
        color: #6c757d;
        font-size: 0.75rem;
    }
    </style>
</x-app-layout>