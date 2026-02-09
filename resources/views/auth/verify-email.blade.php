<x-guest-layout>
    <div class="auth-container">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6">
                    <div class="auth-card p-4 p-md-5 p-lg-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center text-decoration-none">
                                <img src="{{ asset('storage/web_pics/yurei-036.jpeg') }}" alt="YUREI Logo" height="70" class="me-3 rounded">
                                <h2 class="fw-bold text-primary mb-0">YUREI</h2>
                            </a>
                            <p class="text-muted mt-2 fs-5">Youth Rescue and Empowerment Initiative</p>
                        </div>

                        <h3 class="fw-bold text-center mb-4">Verify Your Email</h3>

                        <div class="mb-4 text-center text-muted fs-5">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-4 text-center font-medium text-sm text-success fs-5">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif

                        <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg py-3 fs-5">
                                    <i class="bi bi-envelope me-2"></i>Resend Verification Email
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-lg py-3 fs-5">
                                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 15px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .auth-card {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 40px 50px;
        }
        
        .min-vh-100 {
            min-height: 100vh !important;
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 18px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        @media (max-width: 1200px) {
            .auth-card {
                max-width: 600px;
            }
        }
        
        @media (max-width: 768px) {
            .auth-card {
                margin: 20px;
                padding: 40px 30px !important;
                max-width: 95%;
            }
            
            .container-fluid {
                padding: 0;
            }
            
            .btn-lg {
                padding: 14px 28px;
                font-size: 16px;
            }
        }
        
        @media (max-width: 576px) {
            .auth-card {
                margin: 15px;
                padding: 30px 25px !important;
                border-radius: 15px;
            }
        }
        
        @media (min-width: 1400px) {
            .auth-card {
                max-width: 700px;
            }
        }
    </style>
</x-guest-layout>