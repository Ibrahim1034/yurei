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

                        <h3 class="fw-bold text-center mb-4">Confirm Password</h3>

                        <div class="mb-4 text-center text-muted fs-5">
                            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fs-5">Password</label>
                                <div class="position-relative">
                                    <input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="current-password" />
                                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('password')" style="border: none; background: none;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg py-3 fs-5">
                                    <i class="bi bi-shield-check me-2"></i>Confirm
                                </button>
                            </div>
                        </form>
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
        
        .form-control-lg {
            padding: 15px 20px;
            font-size: 18px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            height: auto;
        }
        
        .form-control-lg:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(1, 136, 128, 0.25);
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 18px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .form-label {
            font-size: 18px;
            font-weight: 500;
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
            
            .form-control-lg {
                padding: 14px 18px;
                font-size: 16px;
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
            
            .form-control-lg {
                padding: 12px 16px;
                font-size: 16px;
            }
        }
        
        @media (min-width: 1400px) {
            .auth-card {
                max-width: 700px;
            }
        }
    </style>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentNode.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</x-guest-layout>