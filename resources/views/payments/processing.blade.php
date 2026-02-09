<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="alert alert-info mb-0">
                                <h4 class="mb-0">Processing Payment</h4>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Processing...</span>
                                </div>
                                <p class="mb-2">We are processing your payment. Please wait...</p>
                                <p class="text-muted small mb-0">This may take a few moments.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-check payment status
        setTimeout(function() {
            checkPaymentStatus('{{ $checkout_request_id }}');
        }, 3000);

        function checkPaymentStatus(checkoutRequestId) {
            fetch('/payment/status?checkout_request_id=' + checkoutRequestId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.status === 'completed') {
                            window.location.href = '{{ route('payment.success') }}?checkout_request_id=' + checkoutRequestId;
                        } else if (data.status === 'failed') {
                            alert('Payment failed: ' + data.result_desc);
                            window.location.reload();
                        } else {
                            setTimeout(function() {
                                checkPaymentStatus(checkoutRequestId);
                            }, 5000);
                        }
                    } else {
                        setTimeout(function() {
                            checkPaymentStatus(checkoutRequestId);
                        }, 5000);
                    }
                })
                .catch(error => {
                    setTimeout(function() {
                        checkPaymentStatus(checkoutRequestId);
                    }, 5000);
                });
        }
    </script>
</x-guest-layout>