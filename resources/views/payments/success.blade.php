<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <!-- Success Icon -->
                        <div class="mb-4">
                            <div class="alert alert-success border-0">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h4 class="mb-0">🎉 Registration Complete!</h4>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Welcome to YUREI!</h6>
                                <p class="text-muted mb-3">
                                    Thank you for completing your registration and payment. Your account is now active and you're officially a member of our community.
                                </p>
                                
                                <h6 class="card-title mt-4">Payment Details</h6>
                                <div class="row small mb-3">
                                    <div class="col-4 text-muted">Name:</div>
                                    <div class="col-8">{{ $user->name }}</div>
                                    
                                    <div class="col-4 text-muted">Membership No:</div>
                                    <div class="col-8">{{ $user->membership_number }}</div>
                                    
                                    <div class="col-4 text-muted">Amount Paid:</div>
                                    <div class="col-8">KES {{ number_format($stkRecord->amount, 2) }}</div>
                                    
                                    <div class="col-4 text-muted">Transaction Date:</div>
                                    <div class="col-8">{{ $stkRecord->transaction_date ?? now()->format('d M Y, H:i') }}</div>
                                    
                                    @if($stkRecord->mpesa_receipt_number)
                                    <div class="col-4 text-muted">M-Pesa Receipt:</div>
                                    <div class="col-8">{{ $stkRecord->mpesa_receipt_number }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Proceed to Login
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                You can now login with your email and password to access all member features.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>