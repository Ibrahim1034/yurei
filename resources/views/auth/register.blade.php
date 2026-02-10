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

                        <h3 class="fw-bold text-center mb-4">Join Our Community</h3>

                        <!-- Progress Steps -->
                        <div class="progress-steps mb-4">
                            <div class="steps">
                                <div class="step active" data-step="1">
                                    <span>1</span>
                                    <small>User Type</small>
                                </div>
                                <div class="step" data-step="2">
                                    <span>2</span>
                                    <small>Personal Info</small>
                                </div>
                                <div class="step" data-step="3">
                                    <span>3</span>
                                    <small>Education</small>
                                </div>
                                <div class="step" data-step="4">
                                    <span>4</span>
                                    <small>Password</small>
                                </div>
                                <div class="step" data-step="5">
                                    <span>5</span>
                                    <small>Payment</small>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                            @csrf

                            <!-- Step 1: User Type Selection -->
                            <div class="step-content active" data-step="1">
                                <h5 class="text-center mb-4">Select Your Registration Type</h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card user-type-card member-card" data-type="member">
                                            <div class="card-body text-center">
                                                <i class="bi bi-person-check-fill text-primary fs-1 mb-3"></i>
                                                <h5 class="card-title">Member</h5>
                                                <p class="card-text text-muted">Full membership with all benefits</p>
                                                <div class="features">
                                                    <small><i class="bi bi-check-circle text-success"></i> YUREI Programs Access</small><br>
                                                    <small><i class="bi bi-check-circle text-success"></i> Membership Card</small><br>
                                                    <!-- <small><i class="bi bi-check-circle text-success"></i> Full access</small> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card user-type-card friend-card" data-type="friend">
                                            <div class="card-body text-center">
                                                <i class="bi bi-people-fill text-success fs-1 mb-3"></i>
                                                <h5 class="card-title">Friend of YUREI</h5>
                                                <p class="card-text text-muted">Support our mission</p>
                                                <div class="features">
                                                   <small><i class="bi bi-check-circle text-success"></i> YUREI Programs Access</small><br>
                                                    <small><i class="bi bi-check-circle text-success"></i> Membership Card</small><br>
                                                    <!-- <small><i class="bi bi-check-circle text-success"></i> Full access</small> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="user_type" id="user_type" value="member">
                                
                                <div class="d-grid gap-2 mt-4">
                                    <button type="button" class="btn btn-primary btn-lg py-3 fs-5 next-step" data-next="2">
                                        Continue <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Personal Information -->
                            <div class="step-content" data-step="2">
                                <h5 class="text-center mb-4">Personal Information</h5>

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label fs-5">Full Name</label>
                                    <input id="name" class="form-control form-control-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="form-label fs-5">Email Address</label>
                                    <input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone_number" class="form-label fs-5">Phone Number</label>
                                    <input id="phone_number" class="form-control form-control-lg" type="text" name="phone_number" :value="old('phone_number')" required autocomplete="tel" placeholder="2547XXXXXXXX" />
                                    <div class="form-text fs-6">Format: 2547XXXXXXXX (e.g., 254712345678)</div>
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <!-- Profile Picture -->
                                <div class="mb-4">
                                    <label for="profile_picture" class="form-label fs-5">Profile Picture</label>
                                    <input id="profile_picture" class="form-control form-control-lg" type="file" name="profile_picture" required accept="image/jpeg,image/png,image/jpg,image/gif" />
                                    <div class="form-text fs-6">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                                    <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                                </div>

                                <!-- Location Details (Only for Members) -->
                                <div id="member-location-fields">
                                    <!-- County -->
                                    <div class="mb-4">
                                        <label for="county" class="form-label fs-5">County</label>
                                        <select id="county" class="form-control form-control-lg" name="county" required>
                                            <option value="">Select County</option>
                                            <!-- Counties will be populated by JavaScript -->
                                        </select>
                                        <x-input-error :messages="$errors->get('county')" class="mt-2" />
                                    </div>

                                    <!-- Constituency -->
                                    <div class="mb-4">
                                        <label for="constituency" class="form-label fs-5">Constituency</label>
                                        <select id="constituency" class="form-control form-control-lg" name="constituency" required disabled>
                                            <option value="">Select Constituency</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('constituency')" class="mt-2" />
                                    </div>

                                    <!-- Ward -->
                                    <div class="mb-4">
                                        <label for="ward" class="form-label fs-5">Ward</label>
                                        <select id="ward" class="form-control form-control-lg" name="ward" required disabled>
                                            <option value="">Select Ward</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('ward')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-lg py-3 fs-5 prev-step" data-prev="1">
                                        <i class="bi bi-arrow-left me-2"></i>Back
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg py-3 fs-5 next-step" data-next="3">
                                        Continue <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Education Information -->
                            <div class="step-content" data-step="3">
                                <h5 class="text-center mb-4">Education Information</h5>

                                <!-- Institution -->
                                <div class="mb-4">
                                    <label for="institution" class="form-label fs-5">University/College</label>
                                    <input id="institution" class="form-control form-control-lg" type="text" name="institution" :value="old('institution')" required />
                                    <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                                </div>

                                <!-- Graduation Status -->
                                <div class="mb-4">
                                    <label for="graduation_status" class="form-label fs-5">Graduation Status</label>
                                    <select id="graduation_status" class="form-control form-control-lg" name="graduation_status" required>
                                        <option value="">Select Status</option>
                                        <option value="studying">Currently Studying</option>
                                        <option value="graduated">Graduated</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('graduation_status')" class="mt-2" />
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-lg py-3 fs-5 prev-step" data-prev="2">
                                        <i class="bi bi-arrow-left me-2"></i>Back
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg py-3 fs-5 next-step" data-next="4">
                                        Continue <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 4: Password -->
                            <div class="step-content" data-step="4">
                                <h5 class="text-center mb-4">Create Password</h5>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fs-5">Password</label>
                                    <div class="position-relative">
                                        <input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="new-password" onkeyup="validatePassword()" />
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('password')" style="border: none; background: none;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Requirements -->
                                    <div class="password-requirements mt-2">
                                        <small class="text-muted">Password must contain:</small>
                                        <div class="requirements-list">
                                            <div class="requirement" id="req-length">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <small>At least 8 characters</small>
                                            </div>
                                            <div class="requirement" id="req-uppercase">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <small>One uppercase letter</small>
                                            </div>
                                            <div class="requirement" id="req-lowercase">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <small>One lowercase letter</small>
                                            </div>
                                            <div class="requirement" id="req-number">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <small>One number</small>
                                            </div>
                                            <div class="requirement" id="req-special">
                                                <i class="bi bi-x-circle text-danger"></i>
                                                <small>One special character</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fs-5">Confirm Password</label>
                                    <div class="position-relative">
                                        <input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" onkeyup="validatePasswordMatch()" />
                                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted" onclick="togglePassword('password_confirmation')" style="border: none; background: none;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Match Indicator -->
                                    <div class="password-match mt-2">
                                        <div class="requirement" id="req-match">
                                            <i class="bi bi-x-circle text-danger"></i>
                                            <small>Passwords must match</small>
                                        </div>
                                    </div>
                                    
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-lg py-3 fs-5 prev-step" data-prev="3">
                                        <i class="bi bi-arrow-left me-2"></i>Back
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg py-3 fs-5" id="completeRegistrationBtn" onclick="validateAndSubmit()">
                                        <i class="bi bi-person-plus me-2"></i>Complete Registration
                                    </button>
                                </div>
                            </div>
                            
                        </form>

                        <div class="text-center mt-5 pt-3">
                            <p class="mb-0 fs-5">Already have an account? 
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Log in</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
        }

        .requirements-list {
            margin-top: 8px;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }

        .requirement i {
            margin-right: 8px;
            font-size: 14px;
        }

        .requirement.valid i {
            color: var(--success);
        }

        .requirement.valid small {
            color: var(--success);
        }

        .requirement.invalid i {
            color: var(--danger);
        }

        .requirement.invalid small {
            color: var(--danger);
        }

        .password-match {
            margin-top: 8px;
        }

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
        
        .progress-steps {
            margin-bottom: 30px;
        }
        
        .steps {
            display: flex;
            justify-content: space-between;
            position: relative;
        }
        
        .steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step span {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 5px;
            border: 3px solid white;
        }
        
        .step.active span {
            background: var(--primary-color);
            color: white;
        }
        
        .step small {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        
        .step.active small {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
        }
        
        .user-type-card {
            cursor: pointer;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .user-type-card:hover,
        .user-type-card.selected {
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .user-type-card.friend-card.selected {
            border-color: var(--success);
        }
        
        .features small {
            display: block;
            margin-bottom: 2px;
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
        
        @media (max-width: 768px) {
            .auth-card {
                margin: 20px;
                padding: 40px 30px !important;
                max-width: 95%;
            }
            
            .steps small {
                font-size: 10px;
            }
            
            .step span {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>

   <script>
    // Password validation functions
    function validatePassword() {
        const password = document.getElementById('password').value;
        
        // Check each requirement
        const hasMinLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        // Update requirement indicators
        updateRequirement('req-length', hasMinLength);
        updateRequirement('req-uppercase', hasUppercase);
        updateRequirement('req-lowercase', hasLowercase);
        updateRequirement('req-number', hasNumber);
        updateRequirement('req-special', hasSpecialChar);
        
        // Also validate password match if confirm password has value
        if (document.getElementById('password_confirmation').value) {
            validatePasswordMatch();
        }
    }

    function validatePasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const passwordsMatch = password === confirmPassword && password !== '';
        
        updateRequirement('req-match', passwordsMatch);
    }

    function updateRequirement(elementId, isValid) {
        const element = document.getElementById(elementId);
        const icon = element.querySelector('i');
        
        if (isValid) {
            element.classList.add('valid');
            element.classList.remove('invalid');
            icon.classList.remove('bi-x-circle', 'text-danger');
            icon.classList.add('bi-check-circle', 'text-success');
        } else {
            element.classList.add('invalid');
            element.classList.remove('valid');
            icon.classList.remove('bi-check-circle', 'text-success');
            icon.classList.add('bi-x-circle', 'text-danger');
        }
    }

    function isPasswordValid() {
        const password = document.getElementById('password').value;
        
        return password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    }

    // UPDATED FUNCTION: Includes loading state and delay
    function validateAndSubmit() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        // Validate password complexity
        if (!isPasswordValid()) {
            alert('Please ensure your password meets all the requirements:\n\n• At least 8 characters\n• One uppercase letter\n• One lowercase letter\n• One number\n• One special character');
            return false;
        }
        
        // Validate password match
        if (password !== confirmPassword) {
            alert('Passwords do not match. Please confirm your password.');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('completeRegistrationBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        submitBtn.disabled = true;
        
        // Disable the form to prevent double submission
        document.getElementById('registrationForm').querySelectorAll('input, select, button').forEach(element => {
            element.disabled = true;
        });
        
        // Add a small delay to show the loading state
        setTimeout(() => {
            document.getElementById('registrationForm').submit();
        }, 500);
        
        return true;
    }

    // Update the existing togglePassword function to also trigger validation
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
        
        // Trigger validation when toggling visibility
        if (fieldId === 'password') {
            validatePassword();
        } else if (fieldId === 'password_confirmation') {
            validatePasswordMatch();
        }
    }
    // Complete Kenya locations data
    const kenyaLocations = {
        'MOMBASA': {
            'CHANGAMWE': ['AIRPORT', 'CHAANI', 'CHANGAMWE', 'KIPEVU', 'PORT REITZ'],
            'JOMVU': ['JOMVU KUU', 'MIKINDANI', 'MIRITINI'],
            'KISAUNI': ['BAMBURI', 'JUNDA', 'MAGOGONI', 'MJAMBERE', 'MTOPANGA', 'MWAKIRUNGE', 'SHANZU'],
            'LIKONI': ['BOFU', 'LIKONI', 'MTONGWE', 'SHIKA ADABU', 'TIMBWANI'],
            'MVITA': ['MAJENGO', 'MJI WA KALE/MAKADARA', 'SHIMANZI/GANJONI', 'TONONOKA', 'TUDOR'],
            'NYALI': ['FRERE TOWN', 'KADZANDANI', 'KONGOWEA', 'MKOMANI', 'ZIWA LA NG\'OMBE']
        },
        'KWALE': {
            'KINANGO': ['CHENGONI/SAMBURU', 'KASEMENI', 'KINANGO', 'MACKINNON-ROAD', 'MWAVUMBO', 'NADAVAYA', 'PUMA'],
            'LUNGALUNGA': ['DZOMBO', 'MWERENI', 'PONGWEKIKONENI', 'VANGA'],
            'MATUGA': ['KUBO SOUTH', 'MKONGANI', 'TIWI', 'TSIMBA GOLINI', 'WAA'],
            'MSAMBWENI': ['GOMBATOBONGWE', 'KINONDO', 'RAMISI', 'UKUNDA']
        },
        'KILIFI': {
            'GANZE': ['BAMBA', 'GANZE', 'JARIBUNI', 'SOKOKE'],
            'KALOLENI': ['KALOLENI', 'KAYAFUNGO', 'MARIAKANI', 'MWANAMWINGA'],
            'KILIFI NORTH': ['DABASO', 'KIBARANI', 'MATSANGONI', 'MNARANI', 'SOKONI', 'TEZO', 'WATAMU'],
            'KILIFI SOUTH': ['CHASIMBA', 'JUNJU', 'MTEPENI', 'MWARAKAYA', 'SHIMO LA TEWA'],
            'MAGARINI': ['ADU', 'GARASHI', 'GONGONI', 'MAGARINI', 'MARAFA', 'SABAKI'],
            'MALINDI': ['GANDA', 'JILORE', 'KAKUYUNI', 'MALINDI TOWN', 'SHELLA'],
            'RABAI': ['KAMBE/RIBE', 'MWAWESA', 'RABAI/KISURUTINI', 'RURUMA']
        },
        'TANA RIVER': {
            'BURA': ['BANGALE', 'BURA', 'CHEWELE', 'MADOGO', 'SALA'],
            'GALOLE': ['CHEWANI', 'KINAKOMBA', 'MIKINDUNI', 'WAYU'],
            'GARSEN': ['GARSEN CENTRAL', 'GARSEN NORTH', 'GARSEN SOUTH', 'GARSEN WEST', 'KIPINI EAST', 'KIPINI WEST']
        },
        'LAMU': {
            'LAMU EAST': ['BASUBA', 'FAZA', 'KIUNGA'],
            'LAMU WEST': ['BAHARI', 'HINDI', 'HONGWE', 'MKOMANI', 'MKUNUMBI', 'SHELLA', 'WITU']
        },
        'TAITA TAVETA': {
            'MWATATE': ['BURA', 'CHAWIA', 'MWATATE', 'RONG\'E', 'WUSI/KISHAMBA'],
            'TAVETA': ['BOMENI', 'CHALA', 'MAHOO', 'MATA', 'MBOGHONI'],
            'VOI': ['KALOLENI', 'KASIGAU', 'MARUNGU', 'MBOLOLO', 'NGOLIA', 'SAGALLA'],
            'WUNDANYI': ['MWANDA/MGANGE', 'WERUGHA', 'WUMINGU/KISHUSHE', 'WUNDANYI/MBALE']
        },
        'GARISSA': {
            'BALAMBALA': ['BALAMBALA', 'DANYERE', 'JARA JARA', 'SAKA', 'SANKURI'],
            'DADAAB': ['ABAKAILE', 'DADAAB', 'DAMAJALE', 'DERTU', 'LABASIGALE', 'LIBOI'],
            'FAFI': ['BURA', 'DEKAHARIA', 'FAFI', 'JARAJILA', 'NANIGHI'],
            'GARISSA TOWNSHIP': ['GALBET', 'IFTIN', 'TOWNSHIP', 'WABERI'],
            'IJARA': ['HULUGHO', 'IJARA', 'MASALANI', 'SANGAILU'],
            'LAGDERA': ['BARAKI', 'BENANE', 'GOREALE', 'MAALIMIN', 'MODOGASHE', 'SABENA']
        },
        'WAJIR': {
            'ELDAS': ['DELLA', 'ELDAS', 'ELNUR/TULA TULA', 'LAKOLEY SOUTH/BASIR'],
            'TARBAJ': ['ELBEN', 'SARMAN', 'TARBAJ', 'WARGADUD'],
            'WAJIR EAST': ['BARWAGO', 'KHOROF/HARAR', 'TOWNSHIP', 'WAGBERI'],
            'WAJIR NORTH': ['BATALU', 'BUTE', 'DANABA', 'GODOMA', 'GURAR', 'KORONDILE', 'MALKAGUFU'],
            'WAJIR SOUTH': ['BENANE', 'BURDER', 'DADAJA BULLA', 'DIIF', 'HABASSWEIN', 'IBRAHIM URE', 'LAGBOGHOL SOUTH'],
            'WAJIR WEST': ['ADEMASAJIDE', 'ARBAJAHAN', 'HADADO/ATHIBOHOL', 'WAGALLA/GANYURE']
        },
        'MANDERA': {
            'BANISSA': ['BANISSA', 'DERKHALE', 'GUBA', 'KILIWEHIRI', 'MALKAMARI'],
            'LAFEY': ['ALUNGO GOF', 'FINO', 'LAFEY', 'LIBEHIA', 'WARANKARA'],
            'MANDERA EAST': ['ARABIA', 'BULLA MPYA', 'KHALALIO', 'NEBOI', 'TOWNSHIP'],
            'MANDERA NORTH': ['ASHABITO', 'GUTICHA', 'MOROTHILE', 'RHAMU', 'RHAMU-DIMTU'],
            'MANDERA SOUTH': ['ELWAK NORTH', 'ELWAK SOUTH', 'KUTULO', 'SHIMBIR FATUMA', 'WARGUDUD'],
            'MANDERA WEST': ['DANDU', 'GITHER', 'LAG SURE', 'TAKABA', 'TAKABA SOUTH']
        },
        'MARSABIT': {
            'LAISAMIS': ['KARGI/SOUTH HORR', 'KORR/NGURUNIT', 'LAISAMIS', 'LOG LOGO', 'LOIYANGALANI'],
            'MOYALE': ['BUTIYE', 'GOLBO', 'HEILU-MANYATTA', 'MOYALE TOWNSHIP', 'OBBU', 'SOLOLO', 'URAN'],
            'NORTH HORR': ['DUKANA', 'ILLERET', 'MAIKONA', 'NORTH HORR', 'TURBI'],
            'SAKU': ['KARARE', 'MARSABIT CENTRAL', 'SAGANTE/JALDESA']
        },
        'ISIOLO': {
            'ISIOLO NORTH': ['BULLA PESA', 'BURAT', 'CHARI', 'CHERAB', 'NGARE MARA', 'OLDONYIRO', 'WABERA'],
            'ISIOLO SOUTH': ['GARBATULLA', 'KINNA', 'SERICHO']
        },
        'MERU': {
            'BUURI': ['KIBIRICHIA', 'KIIRUA/NAARI', 'KISIMA', 'RUIRI/RWARERA', 'TIMAU'],
            'CENTRAL IMENTI': ['ABOTHUGUCHI CENTRAL', 'ABOTHUGUCHI WEST', 'KIAGU', 'MWANGANTHIA'],
            'IGEMBE CENTRAL': ['AKIRANG\'ONDU', 'ATHIRU RUUJINE', 'IGEMBE EAST', 'KANGETA', 'NJIA'],
            'IGEMBE NORTH': ['AMWATHI', 'ANTUAMBUI', 'ANTUBETWE KIONGO', 'NAATHU', 'NTUNENE'],
            'IGEMBE SOUTH': ['AKACHIU', 'ATHIRU GAITI', 'KANUNI', 'KIEGOI/ANTUBOCHIU', 'MAUA'],
            'NORTH IMENTI': ['MUNICIPALITY', 'NTIMA EAST', 'NTIMA WEST', 'NYAKI EAST', 'NYAKI WEST'],
            'SOUTH IMENTI': ['ABOGETA EAST', 'ABOGETA WEST', 'IGOJI EAST', 'IGOJI WEST', 'MITUNGUU', 'NKUENE'],
            'TIGANIA EAST': ['KARAMA', 'KIGUCHWA', 'MIKINDURI', 'MUTHARA', 'THANGATHA'],
            'TIGANIA WEST': ['AKITHII', 'ATHWANA', 'KIANJAI', 'MBEU', 'NKOMO']
        },
        'THARAKA NITHI': {
            'CHUKA/IGAMBANG\'OM': ['IGAMBANG\'OMBE', 'KARINGANI', 'MAGUMONI', 'MARIANI', 'MUGWE'],
            'MAARA': ['CHOGORIA', 'GANGA', 'MITHERU', 'MUTHAMBI', 'MWIMBI'],
            'THARAKA': ['CHIAKARIGA', 'GATUNGA', 'MARIMANTI', 'MUKOTHIMA', 'NKONDI']
        },
        'EMBU': {
            'MANYATTA': ['GATURI SOUTH', 'KIRIMARI', 'KITHIMU', 'MBETI NORTH', 'NGINDA', 'RUGURU/NGANDORI'],
            'MBEERE NORTH': ['EVURORE', 'MUMINJI', 'NTHAWA'],
            'MBEERE SOUTH': ['KIAMBERE', 'MAKIMA', 'MAVURIA', 'MBETI SOUTH', 'MWEA'],
            'RUNYENJES': ['CENTRAL  WARD', 'GATURI NORTH', 'KAGAARI NORTH', 'KAGAARI SOUTH', 'KYENI NORTH', 'KYENI SOUTH']
        },
        'KITUI': {
            'KITUI CENTRAL': ['KYANGWITHYA EAST', 'KYANGWITHYA WEST', 'MIAMBANI', 'MULANGO', 'TOWNSHIP'],
            'KITUI EAST': ['CHULUNI', 'ENDAU/MALALANI', 'MUTITO/KALIKU', 'NZAMBANI', 'VOO/KYAMATU', 'ZOMBE/MWITIKA'],
            'KITUI RURAL': ['KANYANGI', 'KISASI', 'KWAVONZA/YATTA', 'MBITINI'],
            'KITUI SOUTH': ['ATHI', 'IKANGA/KYATUNE', 'IKUTHA', 'KANZIKO', 'MUTHA', 'MUTOMO'],
            'KITUI WEST': ['KAUWI', 'KWA MUTONGA/KITHUMULA', 'MATINYANI', 'MUTONGUNI'],
            'MWINGI CENTRAL': ['CENTRAL', 'KIVOU', 'MUI', 'NGUNI', 'NUU', 'WAITA'],
            'MWINGI NORTH': ['KYUSO', 'MUMONI', 'NGOMENI', 'THARAKA', 'TSEIKURU'],
            'MWINGI WEST': ['KIOMO/KYETHANI', 'KYOME/THAANA', 'MIGWANI', 'NGUUTANI']
        },
        'MACHAKOS': {
            'KANGUNDO': ['KANGUNDO CENTRAL', 'KANGUNDO EAST', 'KANGUNDO NORTH', 'KANGUNDO WEST'],
            'KATHIANI': ['KATHIANI CENTRAL', 'LOWER KAEWA/KAANI', 'MITABONI', 'UPPER KAEWA/IVETI'],
            'MACHAKOS TOWN': ['KALAMA', 'KOLA', 'MACHAKOS CENTRAL', 'MUA', 'MUMBUNI NORTH', 'MUTITUNI', 'MUVUTI/KIIMA-KIMWE'],
            'MASINGA': ['EKALAKALA', 'KIVAA', 'MASINGA CENTRAL', 'MUTHESYA', 'NDITHINI'],
            'MATUNGULU': ['KYELENI', 'MATUNGULU EAST', 'MATUNGULU NORTH', 'MATUNGULU WEST', 'TALA'],
            'MAVOKO': ['ATHI RIVER', 'KINANIE', 'MUTHWANI', 'SYOKIMAU/MULOLONGO'],
            'MWALA': ['KIBAUNI', 'MAKUTANO/ MWALA', 'MASII', 'MBIUNI', 'MUTHETHENI', 'WAMUNYU'],
            'YATTA': ['IKOMBE', 'KATANGI', 'KITHIMANI', 'MATUU', 'NDALANI']
        },
        'MAKUENI': {
            'KAITI': ['ILIMA', 'KEE', 'KILUNGU', 'UKIA'],
            'KIBWEZI WEST': ['EMALI/MULALA', 'IVINGONI/NZAMBANI', 'KIKUMBULYU NORTH', 'KIKUMBULYU SOUTH', 'MAKINDU', 'MASONGALENI', 'MTITO ANDEI', 'NGUU/MASUMBA', 'NGUUMO', 'THANGE'],
            'KILOME': ['KASIKEU', 'KIIMA KIU/KALANZONI', 'MUKAA'],
            'MAKUENI': ['KATHONZWENI', 'KITISE/KITHUKI', 'MAVINDINI', 'MBITINI', 'MUVAU/KIKUUMINI', 'NZAUI/KILILI/KALAMBA', 'WOTE'],
            'MBOONI': ['KALAWA', 'KISAU/KITETA', 'KITHUNGO/KITUNDU', 'MBOONI', 'TULIMANI', 'WAIA/KAKO']
        },
        'NYANDARUA': {
            'KINANGOP': ['ENGINEER', 'GATHARA', 'GITHABAI', 'MAGUMU', 'MURUNGARU', 'NJABINI\\KIBURU', 'NORTH KINANGOP', 'NYAKIO'],
            'KIPIPIRI': ['GETA', 'GITHIORO', 'KIPIPIRI', 'WANJOHI'],
            'NDARAGWA': ['CENTRAL', 'KIRIITA', 'LESHAU PONDO', 'SHAMATA'],
            'OL JOROK': ['CHARAGITA', 'GATHANJI', 'GATIMU', 'WERU'],
            'OL KALOU': ['KAIMBAGA', 'KANJUIRI RIDGE', 'KARAU', 'MIRANGINE', 'RURII']
        },
        'NYERI': {
            'KIENI': ['GAKAWA', 'GATARAKWA', 'KABARU', 'MUGUNDA', 'MWEIGA', 'MWIYOGO/ENDARASHA', 'NAROMORU KIAMATHAGA', 'THEGU RIVER'],
            'MATHIRA': ['IRIAINI', 'KARATINA TOWN', 'KIRIMUKUYU', 'KONYU', 'MAGUTU', 'RUGURU'],
            'MUKURWEINI': ['GIKONDI', 'MUKURWE-INI CENTRAL', 'MUKURWE-INI WEST', 'RUGI'],
            'NYERI TOWN': ['GATITU/MURUGURU', 'KAMAKWA/MUKARO', 'KIGANJO/MATHARI', 'RURING\'U', 'RWARE'],
            'OTHAYA': ['CHINGA', 'IRIA-INI', 'KARIMA', 'MAHIGA'],
            'TETU': ['AGUTHI/GAAKI', 'DEDAN KIMANTHI', 'WAMAGANA']
        },
        'KIRINYAGA': {
            'GICHUGU': ['BARAGWI', 'KABARE', 'KARUMANDI', 'NGARIAMA', 'NJUKIINI'],
            'KIRINYAGA CENTRAL': ['INOI', 'KANYEKI-INI', 'KERUGOYA', 'MUTIRA'],
            'MWEA': ['GATHIGIRIRI', 'KANGAI', 'MURINDUKO', 'MUTITHI', 'NYANGATI', 'TEBERE', 'THIBA', 'WAMUMU'],
            'NDIA': ['KARITI', 'KIINE', 'MUKURE']
        },
        'MURANG\'A': {
            'GATANGA': ['GATANGA', 'ITHANGA', 'KAKUZI/MITUBIRI', 'KARIARA', 'KIHUMBU-INI', 'MUGUMO-INI'],
            'KANDARA': ['GAICHANJIRU', 'ITHIRU', 'KAGUNDU-INI', 'MURUKA', 'NG\'ARARIA', 'RUCHU'],
            'KANGEMA': ['KANYENYAINI', 'MUGURU', 'RWATHIA'],
            'KIGUMO': ['KAHUMBU', 'KANGARI', 'KIGUMO', 'KINYONA', 'MUTHITHI'],
            'KIHARU': ['GATURI', 'MBIRI', 'MUGOIRI', 'MURARANDIA', 'TOWNSHIP', 'WANGU'],
            'MARAGWA': ['ICHAGAKI', 'KAMAHUHA', 'KAMBITI', 'KIMORORI/WEMPA', 'MAKUYU', 'NGINDA'],
            'MATHIOYA': ['GITUGI', 'KAMACHARIA', 'KIRU']
        },
        'KIAMBU': {
            'GATUNDU NORTH': ['CHANIA', 'GITHOBOKONI', 'GITUAMBA', 'MANG\'U'],
            'GATUNDU SOUTH': ['KIAMWANGI', 'KIGANJO', 'NDARUGU', 'NGENDA'],
            'GITHUNGURI': ['GITHIGA', 'GITHUNGURI', 'IKINU', 'KOMOTHAI', 'NGEWA'],
            'JUJA': ['JUJA', 'KALIMONI', 'MURERA', 'THETA', 'WITEITHIE'],
            'KABETE': ['GITARU', 'KABETE', 'MUGUGA', 'NYADHUNA', 'UTHIRU'],
            'KIAMBAA': ['CIANDA', 'KARURI', 'KIHARA', 'MUCHATHA', 'NDENDERU'],
            'KIAMBU': ['NDUMBERI', 'RIABAI', 'TING\'ANG\'A', 'TOWNSHIP'],
            'KIKUYU': ['KARAI', 'KIKUYU', 'KINOO', 'NACHU', 'SIGONA'],
            'LARI': ['KAMBURU', 'KIJABE', 'KINALE', 'LARI/KIRENGA', 'NYANDUMA'],
            'LIMURU': ['BIBIRIONI', 'LIMURU CENTRAL', 'LIMURU EAST', 'NDEIYA', 'NGECHA TIGONI'],
            'RUIRU': ['BIASHARA', 'GATONGORA', 'GITOTHUA', 'KAHAWA SUKARI', 'KAHAWA WENDANI', 'KIUU', 'MWIHOKO', 'MWIKI'],
            'THIKA TOWN': ['GATUANYAGA', 'HOSPITAL', 'KAMENU', 'NGOLIBA', 'TOWNSHIP']
        },
        'TURKANA': {
            'LOIMA': ['KOTARUK/LOBEI', 'LOIMA', 'LOKIRIAMA/LORENGIPPI', 'TURKWEL'],
            'TURKANA CENTRAL': ['KALOKOL', 'KANAMKEMER', 'KANG\'ATOTHA', 'KERIO DELTA', 'LODWAR TOWNSHIP'],
            'TURKANA EAST': ['KAPEDO/NAPEITOM', 'KATILIA', 'LOKORI/KOCHODIN'],
            'TURKANA NORTH': ['KAALENG/KAIKOR', 'KAERIS', 'KIBISH', 'LAKE ZONE', 'LAPUR', 'NAKALALE'],
            'TURKANA SOUTH': ['KALAPATA', 'KAPUTIR', 'KATILU', 'LOBOKAT', 'LOKICHAR'],
            'TURKANA WEST': ['KAKUMA', 'KALOBEYEI', 'LETEA', 'LOKICHOGGIO', 'LOPUR', 'NANAAM', 'SONGOT']
        },
        'WEST POKOT': {
            'KACHELIBA': ['ALALE', 'KAPCKOK', 'KASEI', 'KIWAWA', 'KODICH', 'SUAM'],
            'KAPENGURIA': ['ENDUGH', 'KAPENGURIA', 'MNAGEI', 'RIWO', 'SIYOI', 'SOOK'],
            'POKOT SOUTH': ['BATEI', 'CHEPARERIA', 'LELAN', 'TAPACH'],
            'SIGOR': ['LOMUT', 'MASOOL', 'SEKERR', 'WEIWEI']
        },
        'SAMBURU': {
            'SAMBURU EAST': ['WAMBA EAST', 'WAMBA NORTH', 'WAMBA WEST', 'WASO'],
            'SAMBURU NORTH': ['ANGATA NANYOKIE', 'BAAWA', 'EL-BARTA', 'NACHOLA', 'NDOTO', 'NYIRO'],
            'SAMBURU WEST': ['LODOKEJEK', 'LOOSUK', 'MARALAL', 'PORO', 'SUGUTA MARMAR']
        },
        'TRANS NZOIA': {
            'CHERANGANY': ['CHEPSIRO/KIPTOROR', 'CHERANGANY/SUWERWA', 'KAPLAMAI', 'MAKUTANO', 'MOTOSIET', 'SINYERERE', 'SITATUNGA'],
            'ENDEBESS': ['CHEPCHOINA', 'ENDEBESS', 'MATUMBEI'],
            'KIMININI': ['HOSPITAL', 'KIMININI', 'NABISWA', 'SIKHENDU', 'SIRENDE', 'WAITALUK'],
            'KWANZA': ['BIDII', 'KAPOMBOI', 'KEIYO', 'KWANZA'],
            'SABOTI': ['KINYORO', 'MACHEWA', 'MATISI', 'SABOTI', 'TUWANI']
        },
        'UASIN GISHU': {
            'AINABKOI': ['AINABKOI/OLARE', 'KAPSOYA', 'KAPTAGAT'],
            'KAPSERET': ['KIPKENYO', 'LANGAS', 'MEGUN', 'NGERIA', 'SIMAT/KAPSERET'],
            'KESSES': ['CHEPTIRET/KIPCHAMO', 'RACECOURSE', 'TARAKWA', 'TULWET/CHUIYAT'],
            'MOIBEN': ['KARUNA/MEIBEKI', 'KIMUMU', 'MOIBEN', 'SERGOIT', 'TEMBELIO'],
            'SOY': ['KAPKURES', 'KIPSOMBA', 'KUINET/KAPSUSWA', 'MOI\'S BRIDGE', 'SEGERO/BARSOMBE', 'SOY', 'ZIWA'],
            'TURBO': ['HURUMA', 'KAMAGUT', 'KAPSAOS', 'KIPLOMBE', 'NGENYILEL', 'TAPSAGOI']
        },
        'ELGEYO MARAKWET': {
            'KEIYO NORTH': ['EMSOO', 'KAMARINY', 'KAPCHEMUTWA', 'TAMBACH'],
            'KEIYO SOUTH': ['CHEPKORIO', 'KABIEMIT', 'KAPTARAKWA', 'METKEI', 'SOY NORTH', 'SOY SOUTH'],
            'MARAKWET EAST': ['EMBOBUT / EMBULOT', 'ENDO', 'KAPYEGO', 'SAMBIRIR'],
            'MARAKWET WEST': ['ARROR', 'CHERANG\'ANY/CHEBORORWA', 'KAPSOWAR', 'LELAN', 'MOIBEN/KUSERWO', 'SENGWER']
        },
        'NANDI': {
            'ALDAI': ['KABWARENG', 'KAPTUMO-KABOI', 'KEMELOI-MARABA', 'KOBUJOI', 'KOYO-NDURIO', 'TERIK'],
            'CHESUMEI': ['CHEMUNDU/KAPNG\'ETUNY', 'KAPTEL/KAMOIYWO', 'KIPTUYA', 'KOSIRAI', 'LELMOKWO/NGECHEK'],
            'EMGWEN': ['CHEPKUMIA', 'KAPKANGANI', 'KAPSABET', 'KILIBWONI'],
            'MOSOP': ['CHEPTERWAI', 'KABISAGA', 'KABIYET', 'KIPKAREN', 'KURGUNG/SURUNGAI', 'NDALAT', 'SANGALO/KEBULONIK'],
            'NANDI HILLS': ['CHEPKUNYUK', 'KAPCHORUA', 'NANDI HILLS', 'OL\'LESSOS'],
            'TINDERET': ['CHEMELIL/CHEMASE', 'KAPSIMOTWO', 'SONGHOR/SOBA', 'TINDIRET']
        },
        'BARINGO': {
            'BARINGO  NORTH': ['BARTABWA', 'BARWESSA', 'KABARTONJO', 'SAIMO/KIPSARAMAN', 'SAIMO/SOI'],
            'BARINGO CENTRAL': ['EWALEL CHAPCHAP', 'KABARNET', 'KAPROPITA', 'SACHO', 'TENGES'],
            'BARINGO SOUTH': ['ILCHAMUS', 'MARIGAT', 'MOCHONGOI', 'MUKUTANI'],
            'ELDAMA RAVINE': ['KOIBATEK', 'LEMBUS', 'LEMBUS KWEN', 'LEMBUS/PERKERRA', 'MUMBERES/MAJI MAZURI', 'RAVINE'],
            'MOGOTIO': ['EMINING', 'KISANANA', 'MOGOTIO'],
            'TIATY': ['CHURO/AMAYA', 'KOLOWA', 'LOIYAMOROCK', 'RIBKWO', 'SILALE', 'TANGULBEI/KOROSSI', 'TIRIOKO']
        },
        'LAIKIPIA': {
            'LAIKIPIA EAST': ['NANYUKI', 'NGOBIT', 'THINGITHU', 'TIGITHI', 'UMANDE'],
            'LAIKIPIA NORTH': ['MUKOGONDO EAST', 'MUKOGONDO WEST', 'SEGERA', 'SOSIAN'],
            'LAIKIPIA WEST': ['IGWAMITI', 'KINAMBA', 'MARMANET', 'OLMORAN', 'RUMURUTI TOWNSHIP', 'SALAMA']
        },
        'NAKURU': {
            'BAHATI': ['BAHATI', 'DUNDORI', 'KABATINI', 'KIAMAINA', 'LANET/UMOJA'],
            'GILGIL': ['ELEMENTAITA', 'GILGIL', 'MALEWA WEST', 'MBARUK/EBURU', 'MURINDATI'],
            'KURESOI NORTH': ['KAMARA', 'KIPTORORO', 'NYOTA', 'SIRIKWA'],
            'KURESOI SOUTH': ['AMALO', 'KERINGET', 'KIPTAGICH', 'TINET'],
            'MOLO': ['ELBURGON', 'MARIASHONI', 'MOLO', 'TURI'],
            'NAIVASHA': ['BIASHARA', 'HELLS GATE', 'LAKEVIEW', 'MAAI-MAHIU', 'MAIELLA', 'NAIVASHA EAST', 'OLKARIA', 'VIWANDANI'],
            'NAKURU TOWN EAST': ['BIASHARA', 'FLAMINGO', 'KIVUMBINI', 'MENENGAI', 'NAKURU EAST'],
            'NAKURU TOWN WEST': ['BARUT', 'KAPKURES', 'KAPTEMBWO', 'LONDON', 'RHODA', 'SHAABAB'],
            'NJORO': ['KIHINGO', 'LARE', 'MAUCHE', 'MAUNAROK', 'NESSUIT', 'NJORO'],
            'RONGAI': ['MENENGAI WEST', 'MOSOP', 'SOIN', 'SOLAI', 'VISOI'],
            'SUBUKIA': ['KABAZI', 'SUBUKIA', 'WASEGES']
        },
        'NAROK': {
            'EMURUA DIKIRR': ['ILKERIN', 'KAPSASIAN', 'MOGONDO', 'OLOLMASANI'],
            'KILGORIS': ['ANGATA BARIKOI', 'KEYIAN', 'KILGORIS CENTRAL', 'KIMINTET', 'LOLGORIAN', 'SHANKOE'],
            'NAROK EAST': ['ILDAMAT', 'KEEKONYOKIE', 'MOSIRO', 'SUSWA'],
            'NAROK NORTH': ['MELILI', 'NAROK TOWN', 'NKARETA', 'OLOKURTO', 'OLORROPIL', 'OLPUSIMORU'],
            'NAROK SOUTH': ['LOITA', 'MAJIMOTO/NAROOSURA', 'MELELO', 'OLOLULUNG\'A', 'SAGAMIAN', 'SOGOO'],
            'NAROK WEST': ['ILMOTIOK', 'MARA', 'NAIKARRA', 'SIANA']
        },
        'KAJIADO': {
            'KAJIADO CENTRAL': ['DALALEKUTUK', 'ILDAMAT', 'MATAPATO NORTH', 'MATAPATO SOUTH', 'PURKO'],
            'KAJIADO EAST': ['IMARORO', 'KAPUTIEI NORTH', 'KENYAWA-POKA', 'KITENGELA', 'OLOOSIRKON/SHOLINKE'],
            'KAJIADO NORTH': ['NGONG', 'NKAIMURUNYA', 'OLKERI', 'OLOOLUA', 'ONGATA RONGAI'],
            'KAJIADO SOUTH': ['ENTONET/LENKISIM', 'KIMANA', 'KUKU', 'MBIRIKANI/ESELENKEI', 'ROMBO'],
            'KAJIADO WEST': ['EWUASO OONKIDONG\'I', 'ILOODOKILANI', 'KEEKONYOKIE', 'MAGADI', 'MOSIRO']
        },
        'KERICHO': {
            'AINAMOI': ['AINAMOI', 'KAPKUGERWET', 'KAPSAOS', 'KAPSOIT', 'KIPCHEBOR', 'KIPCHIMCHIM'],
            'BELGUT': ['CHAIK', 'CHEPTORORIET/SERETUT', 'KABIANGA', 'KAPSUSER', 'WALDAI'],
            'BURETI': ['CHEBOIN', 'CHEMOSOT', 'CHEPLANGET', 'KAPKATET', 'KISIARA', 'LITEIN', 'TEBESONIK'],
            'KIPKELION EAST': ['CHEPSEON', 'KEDOWA/KIMUGUL', 'LONDIANI', 'TENDENO/SORGET'],
            'KIPKELION WEST': ['CHILCHILA', 'KAMASIAN', 'KIPKELION', 'KUNYAK'],
            'SIGOWET/SOIN': ['KAPLELARTET', 'SIGOWET', 'SOIN', 'SOLIAT']
        },
        'BOMET': {
            'BOMET CENTRAL': ['CHESOEN', 'MUTARAKWA', 'NDARAWETA', 'SILIBWET TOWNSHIP', 'SINGORWET'],
            'BOMET EAST': ['CHEMANER', 'KEMBU', 'KIPRERES', 'LONGISA', 'MERIGI'],
            'CHEPALUNGU': ['CHEBUNYO', 'KONG\'ASIS', 'NYANGORES', 'SIGOR', 'SIONGIROI'],
            'KONOIN': ['BOITO', 'CHEPCHABAS', 'EMBOMOS', 'KIMULOT', 'MOGOGOSIEK'],
            'SOTIK': ['CHEMAGEL', 'KAPLETUNDO', 'KIPSONOI', 'NDANAI/ABOSI', 'RONGENA/MANARET']
        },
        'KAKAMEGA': {
            'BUTERE': ['MARAMA CENTRAL', 'MARAMA NORTH', 'MARAMA SOUTH', 'MARAMA WEST', 'MARENYO - SHIANDA'],
            'IKOLOMANI': ['IDAKHO CENTRAL', 'IDAKHO EAST', 'IDAKHO NORTH', 'IDAKHO SOUTH'],
            'KHWISERO': ['KISA CENTRAL', 'KISA EAST', 'KISA NORTH', 'KISA WEST'],
            'LIKUYANI': ['KONGONI', 'LIKUYANI', 'NZOIA', 'SANGO', 'SINOKO'],
            'LUGARI': ['CHEKALINI', 'CHEVAYWA', 'LUGARI', 'LUMAKANDA', 'LWANDETI', 'MAUTUMA'],
            'LURAMBI': ['BUTSOTSO CENTRAL', 'BUTSOTSO EAST', 'BUTSOTSO SOUTH', 'MAHIAKALO', 'SHEYWE', 'SHIRERE'],
            'MALAVA': ['BUTALI/CHEGULO', 'CHEMUCHE', 'EAST KABRAS', 'MANDA-SHIVANGA', 'SHIRUGU-MUGAI', 'SOUTH KABRAS', 'WEST KABRAS'],
            'MATUNGU': ['KHALABA', 'KHOLERA', 'KOYONZO', 'MAYONI', 'NAMAMALI'],
            'MUMIAS EAST': ['EAST WANGA', 'ISONGO/MAKUNGA/MALAHA', 'LUBINU/LUSHEYA'],
            'MUMIAS WEST': ['ETENJE', 'MUMIAS CENTRAL', 'MUMIAS NORTH', 'MUSANDA'],
            'NAVAKHOLO': ['BUNYALA CENTRAL', 'BUNYALA EAST', 'BUNYALA WEST', 'INGOSTSE-MATHIA', 'SHINOYI-SHIKOMARI-'],
            'SHINYALU': ['ISUKHA CENTRAL', 'ISUKHA EAST', 'ISUKHA NORTH', 'ISUKHA SOUTH', 'ISUKHA WEST', 'MURHANDA']
        },
        'VIHIGA': {
            'EMUHAYA': ['CENTRAL BUNYORE', 'NORTH EAST BUNYORE', 'WEST BUNYORE'],
            'HAMISI': ['BANJA', 'GISAMBAI', 'JEPKOYAI', 'MUHUDU', 'SHAMAKHOKHO', 'SHIRU', 'TAMBUA'],
            'LUANDA': ['EMABUNGO', 'LUANDA SOUTH', 'LUANDA TOWNSHIP', 'MWIBONA', 'WEMILABI'],
            'SABATIA': ['BUSALI', 'CHAVAKALI', 'LYADUYWA/IZAVA', 'NORTH MARAGOLI', 'WEST SABATIA', 'WODANGA'],
            'VIHIGA': ['CENTRAL MARAGOLI', 'LUGAGA-WAMULUMA', 'MUNGOMA', 'SOUTH MARAGOLI']
        },
        'BUNGOMA': {
            'BUMULA': ['BUMULA', 'KABULA', 'KHASOKO', 'KIMAETI', 'SIBOTI', 'SOUTH BUKUSU', 'WEST BUKUSU'],
            'KABUCHAI': ['BWAKE/LUUYA', 'KABUCHAI/CHWELE', 'MUKUYUNI', 'WEST NALONDO'],
            'KANDUYI': ['BUKEMBE EAST', 'BUKEMBE WEST', 'EAST SANG\'ALO', 'KHALABA', 'MARAKARU/TUUTI', 'MUSIKOMA', 'SANG\'ALO WEST', 'TOWNSHIP'],
            'KIMILILI': ['KAMUKUYWA', 'KIBINGEI', 'KIMILILI', 'MAENI'],
            'MT.ELGON': ['CHEPTAIS', 'CHEPYUK', 'CHESIKAKI', 'ELGON', 'KAPKATENY', 'KAPTAMA'],
            'SIRISIA': ['LWANDANYI', 'MALAKISI/SOUTH KULISIRU', 'NAMWELA'],
            'TONGAREN': ['MBAKALO', 'MILIMA', 'NAITIRI/KABUYEFWE', 'NDALU/ TABANI', 'SOYSAMBU/ MITUA', 'TONGAREN'],
            'WEBUYE EAST': ['MARAKA', 'MIHUU', 'NDIVISI'],
            'WEBUYE WEST': ['BOKOLI', 'MATULO', 'MISIKHU', 'SITIKHO']
        },
        'BUSIA': {
            'BUDALANGI': ['BUNYALA CENTRAL', 'BUNYALA NORTH', 'BUNYALA SOUTH', 'BUNYALA WEST'],
            'BUTULA': ['ELUGULU', 'KINGANDOLE', 'MARACHI CENTRAL', 'MARACHI EAST', 'MARACHI NORTH', 'MARACHI WEST'],
            'FUNYULA': ['AGENG\'A NANGUBA', 'BWIRI', 'NAMBOBOTO NAMBUKU', 'NANGINA'],
            'MATAYOS': ['BUKHAYO WEST', 'BURUMBA', 'BUSIBWABO', 'MATAYOS SOUTH', 'MAYENJE'],
            'NAMBALE': ['BUKHAYO CENTRAL', 'BUKHAYO EAST', 'BUKHAYO NORTH/WALTSI', 'NAMBALE TOWNSHIP'],
            'TESO NORTH': ['ANG\'URAI EAST', 'ANG\'URAI NORTH', 'ANG\'URAI SOUTH', 'MALABA CENTRAL', 'MALABA NORTH', 'MALABA SOUTH'],
            'TESO SOUTH': ['AMUKURA CENTRAL', 'AMUKURA EAST', 'AMUKURA WEST', 'ANG\'OROM', 'CHAKOL NORTH', 'CHAKOL SOUTH']
        },
        'SIAYA': {
            'ALEGO USONGA': ['CENTRAL ALEGO', 'NORTH ALEGO', 'SIAYA TOWNSHIP', 'SOUTH EAST ALEGO', 'USONGA', 'WEST ALEGO'],
            'BONDO': ['CENTRAL SAKWA', 'NORTH SAKWA', 'SOUTH SAKWA', 'WEST SAKWA', 'WEST YIMBO', 'YIMBO EAST'],
            'GEM': ['CENTRAL GEM', 'EAST GEM', 'NORTH GEM', 'SOUTH GEM', 'WEST GEM', 'YALA TOWNSHIP'],
            'RARIEDA': ['EAST ASEMBO', 'NORTH UYOMA', 'SOUTH UYOMA', 'WEST ASEMBO', 'WEST UYOMA'],
            'UGENYA': ['EAST UGENYA', 'NORTH UGENYA', 'UKWALA', 'WEST UGENYA'],
            'UGUNJA': ['SIDINDI', 'SIGOMERE', 'UGUNJA']
        },
        'KISUMU': {
            'KISUMU CENTRAL': ['KONDELE', 'MARKET MILIMANI', 'MIGOSI', 'NYALENDA B', 'RAILWAYS', 'SHAURIMOYO KALOLENI'],
            'KISUMU EAST': ['KAJULU', 'KOLWA CENTRAL', 'KOLWA EAST', 'MANYATTA \'B\'', 'NYALENDA \'A\''],
            'KISUMU WEST': ['CENTRAL KISUMU', 'KISUMU NORTH', 'NORTH WEST KISUMU', 'SOUTH WEST KISUMU', 'WEST KISUMU'],
            'MUHORONI': ['CHEMELIL', 'MASOGO/NYANG\'OMA', 'MIWANI', 'MUHORONI/KORU', 'OMBEYI'],
            'NYAKACH': ['CENTRAL NYAKACH', 'NORTH NYAKACH', 'SOUTH EAST NYAKACH', 'SOUTH WEST NYAKACH', 'WEST NYAKACH'],
            'NYANDO': ['AHERO', 'AWASI/ONJIKO', 'EAST KANO/WAWIDHI', 'KABONYO/KANYAGWAL', 'KOBURA'],
            'SEME': ['CENTRAL SEME', 'EAST SEME', 'NORTH SEME', 'WEST SEME']
        },
        'HOMA BAY': {
            'HOMA BAY TOWN': ['HOMA BAY ARUJO', 'HOMA BAY CENTRAL', 'HOMA BAY EAST', 'HOMA BAY WEST'],
            'KABONDO KASIPUL': ['KABONDO EAST', 'KABONDO WEST', 'KOJWACH', 'KOKWANYO/KAKELO'],
            'KARACHUONYO': ['CENTRAL', 'KANYALUO', 'KENDU BAY TOWN', 'KIBIRI', 'NORTH KARACHUONYO', 'WANGCHIENG', 'WEST KARACHUONYO'],
            'KASIPUL': ['CENTRAL KASIPUL', 'EAST KAMAGAK', 'SOUTH KASIPUL', 'WEST KAMAGAK', 'WEST KASIPUL'],
            'MBITA': ['GEMBE', 'KASGUNGA', 'LAMBWE', 'MFANGANO ISLAND', 'RUSINGA ISLAND'],
            'NDHIWA': ['KABUOCH SOUTH/PALA', 'KANYADOTO', 'KANYAMWA KOLOGI', 'KANYAMWA KOSEWE', 'KANYIKELA', 'KWABWAI', 'NORTH KABUOCH'],
            'RANGWE': ['EAST GEM', 'KAGAN', 'KOCHIA', 'WEST GEM'],
            'SUBA': ['GWASSI NORTH', 'GWASSI SOUTH', 'KAKSINGRI WEST', 'RUMA KAKSINGRI EAST']
        },
        'MIGORI': {
            'AWENDO': ['CENTRAL SAKWA', 'NORTH SAKWA', 'SOUTH SAKWA', 'WEST SAKWA'],
            'KURIA EAST': ['GOKEHARAKA/GETAMBWEGA', 'NTIMARU EAST', 'NTIMARU WEST', 'NYABASI EAST', 'NYABASI WEST'],
            'KURIA WEST': ['BUKIRA CENTRL/IKEREGE', 'BUKIRA EAST', 'ISIBANIA', 'MAKERERO', 'MASABA', 'NYAMOSENSE/KOMOSOKO', 'TAGARE'],
            'NYATIKE': ['GOT KACHOLA', 'KACHIEN\'G', 'KALER', 'KANYASA', 'MACALDER/KANYARWANDA', 'MUHURU', 'NORTH KADEM'],
            'RONGO': ['CENTRAL KAMAGAMBO', 'EAST KAMAGAMBO', 'NORTH KAMAGAMBO', 'SOUTH KAMAGAMBO'],
            'SUNA EAST': ['GOD JOPE', 'KAKRAO', 'KWA', 'SUNA CENTRAL'],
            'SUNA WEST': ['RAGANA-ORUBA', 'WASIMBETE', 'WASWETA II', 'WIGA'],
            'URIRI': ['CENTRAL KANYAMKAGO', 'EAST KANYAMKAGO', 'NORTH KANYAMKAGO', 'SOUTH KANYAMKAGO', 'WEST KANYAMKAGO']
        },
        'KISII': {
            'BOBASI': ['BOBASI BOGETAORIO', 'BOBASI BOITANGARE', 'BOBASI CENTRAL', 'BOBASI CHACHE', 'MASIGE EAST', 'MASIGE WEST', 'NYACHEKI', 'SAMETA/MOKWERERO'],
            'BOMACHOGE BORABU': ['BOKIMONGE', 'BOMBABA BORABU', 'BOOCHI BORABU', 'MAGENCHE'],
            'BOMACHOGE CHACHE': ['BOOCHI/TENDERE', 'BOSOTI/SENGERA', 'MAJOGE'],
            'BONCHARI': ['BOGIAKUMU', 'BOMARIBA', 'BOMORENDA', 'RIANA'],
            'KITUTU CHACHE NORTH': ['KEGOGI', 'MARANI', 'MONYERERO', 'SENSI'],
            'KITUTU CHACHE SOUTH': ['BOGEKA', 'BOGUSERO', 'KITUTU   CENTRAL', 'NYAKOE', 'NYATIEKO'],
            'NYARIBARI CHACHE': ['BIRONGO', 'BOBARACHO', 'IBENO', 'KEUMBU', 'KIOGORO', 'KISII CENTRAL'],
            'NYARIBARI MASABA': ['GESUSU', 'ICHUNI', 'KIAMOKAMA', 'MASIMBA', 'NYAMASIBI'],
            'SOUTH MUGIRANGO': ['BOGETENGA', 'BOIKANG\'A', 'BORABU / CHITAGO', 'GETENGA', 'MOTICHO', 'TABAKA']
        },
        'NYAMIRA': {
            'BORABU': ['ESISE', 'KIABONYORU', 'MEKENENE', 'NYANSIONGO'],
            'KITUTU MASABA': ['GACHUBA', 'GESIMA', 'KEMERA', 'MAGOMBO', 'MANGA', 'RIGOMA'],
            'NORTH MUGIRANGO': ['BOKEIRA', 'BOMWAGAMO', 'EKERENYO', 'ITIBO', 'MAGWAGWA'],
            'WEST MUGIRANGO': ['BOGICHORA', 'BONYAMATUTA', 'BOSAMARO', 'NYAMAIYA', 'TOWNSHIP']
        },
        'NAIROBI': {
            'DAGORETTI NORTH': ['GATINA', 'KABIRO', 'KAWANGWARE', 'KILELESHWA', 'KILIMANI'],
            'DAGORETTI SOUTH': ['MUTUINI', 'NGANDO', 'RIRUTA', 'UTHIRU/RUTHIMITU', 'WAITHAKA'],
            'EMBAKASI CENTRAL': ['KAYOLE CENTRAL', 'KAYOLE NORTH', 'KAYOLE SOUTH', 'KOMAROCK', 'MATOPENI'],
            'EMBAKASI EAST': ['EMBAKASI', 'LOWER SAVANNAH', 'MIHANGO', 'UPPER SAVANNAH', 'UTAWALA'],
            'EMBAKASI NORTH': ['DANDORA AREA I', 'DANDORA AREA II', 'DANDORA AREA III', 'DANDORA AREA IV', 'KARIOBANGI NORTH'],
            'EMBAKASI SOUTH': ['IMARA DAIMA', 'KWA NJENGA', 'KWA REUBEN', 'KWARE', 'PIPELINE'],
            'EMBAKASI WEST': ['KARIOBANGI SOUTH', 'MOWLEM', 'UMOJA I', 'UMOJA II'],
            'KAMUKUNJI': ['AIRBASE', 'CALIFORNIA', 'EASTLEIGH NORTH', 'EASTLEIGH SOUTH', 'PUMWANI'],
            'KASARANI': ['CLAYCITY', 'KASARANI', 'MWIKI', 'NJIRU', 'RUAI'],
            'KIBRA': ['LAINI SABA', 'LINDI', 'MAKINA', 'SARANGOMBE', 'WOODLEY/KENYATTA GOLF'],
            'LANGATA': ['KAREN', 'MUGUMO-INI', 'NAIROBI WEST', 'NYAYO HIGHRISE', 'SOUTH-C'],
            'MAKADARA': ['HARAMBEE', 'MAKONGENI', 'MARINGO/HAMZA', 'VIWANDANI'],
            'MATHARE': ['HOSPITAL', 'HURUMA', 'KIAMAIKO', 'MABATINI', 'MLANGO KUBWA', 'NGEI'],
            'ROYSAMBU': ['GITHURAI', 'KAHAWA', 'KAHAWA WEST', 'ROYSAMBU', 'ZIMMERMAN'],
            'RUARAKA': ['BABA DOGO', 'KOROGOCHO', 'LUCKY SUMMER', 'MATHARE NORTH', 'UTALII'],
            'STAREHE': ['LANDIMAWE', 'NAIROBI CENTRAL', 'NAIROBI SOUTH', 'NGARA', 'PANGANI', 'ZIWANI/KARIOKOR'],
            'WESTLANDS': ['KANGEMI', 'KARURA', 'KITISURU', 'MOUNTAIN VIEW', 'PARKLANDS/HIGHRIDGE']
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Step navigation
        const steps = document.querySelectorAll('.step-content');
        const stepButtons = document.querySelectorAll('.step');
        
        // User type selection
        const userTypeCards = document.querySelectorAll('.user-type-card');
        const userTypeInput = document.getElementById('user_type');
        const memberLocationFields = document.getElementById('member-location-fields');
        
        // Location selects
        const countySelect = document.getElementById('county');
        const constituencySelect = document.getElementById('constituency');
        const wardSelect = document.getElementById('ward');

        // User type selection
        userTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                userTypeCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                userTypeInput.value = this.dataset.type;
                
                // Show/hide location fields based on user type
                if (this.dataset.type === 'member') {
                    memberLocationFields.style.display = 'block';
                    // Make location fields required
                    countySelect.required = true;
                    constituencySelect.required = true;
                    wardSelect.required = true;
                } else {
                    memberLocationFields.style.display = 'none';
                    // Remove required attribute for friends
                    countySelect.required = false;
                    constituencySelect.required = false;
                    wardSelect.required = false;
                    // Clear location fields
                    countySelect.value = '';
                    constituencySelect.innerHTML = '<option value="">Select Constituency</option>';
                    constituencySelect.disabled = true;
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
                    wardSelect.disabled = true;
                }
            });
        });

        // Next step buttons
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = this.closest('.step-content');
                const nextStepNum = this.dataset.next;
                
                // Validate current step before proceeding
                if (validateStep(currentStep.dataset.step)) {
                    showStep(nextStepNum);
                }
            });
        });

        // Previous step buttons
        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', function() {
                const prevStepNum = this.dataset.prev;
                showStep(prevStepNum);
            });
        });

        function showStep(stepNum) {
            // Hide all steps
            steps.forEach(step => step.classList.remove('active'));
            
            // Show selected step
            document.querySelector(`.step-content[data-step="${stepNum}"]`).classList.add('active');
            
            // Update progress steps
            stepButtons.forEach(step => {
                if (parseInt(step.dataset.step) <= parseInt(stepNum)) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }

        function validateStep(step) {
            const currentStep = document.querySelector(`.step-content[data-step="${step}"]`);
            const inputs = currentStep.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Special validation for step 1 (user type)
            if (step === '1' && !userTypeInput.value) {
                isValid = false;
                alert('Please select a user type');
            }

            return isValid;
        }

        // Load counties from the kenyaLocations data
        function loadCounties() {
            countySelect.innerHTML = '<option value="">Select County</option>';
            
            Object.keys(kenyaLocations).sort().forEach(county => {
                const option = document.createElement('option');
                option.value = county;
                option.textContent = county;
                countySelect.appendChild(option);
            });
        }

        // County change event
        countySelect.addEventListener('change', function() {
            const county = this.value;
            if (county) {
                // Enable and load constituencies
                constituencySelect.disabled = false;
                loadConstituencies(county);
            } else {
                constituencySelect.disabled = true;
                wardSelect.disabled = true;
                constituencySelect.innerHTML = '<option value="">Select Constituency</option>';
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
            }
        });

        // Constituency change event
        constituencySelect.addEventListener('change', function() {
            const constituency = this.value;
            if (constituency) {
                // Enable and load wards
                wardSelect.disabled = false;
                loadWards(constituency);
            } else {
                wardSelect.disabled = true;
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
            }
        });

        function loadConstituencies(county) {
            constituencySelect.innerHTML = '<option value="">Select Constituency</option>';
            
            if (kenyaLocations[county]) {
                Object.keys(kenyaLocations[county]).sort().forEach(constituency => {
                    const option = document.createElement('option');
                    option.value = constituency;
                    option.textContent = constituency;
                    constituencySelect.appendChild(option);
                });
            }
        }

        function loadWards(constituency) {
            wardSelect.innerHTML = '<option value="">Select Ward</option>';
            
            const county = countySelect.value;
            if (kenyaLocations[county] && kenyaLocations[county][constituency]) {
                kenyaLocations[county][constituency].sort().forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward;
                    option.textContent = ward;
                    wardSelect.appendChild(option);
                });
            }
        }

        // Initialize
        loadCounties();
        
        // Set default user type
        document.querySelector('.member-card').classList.add('selected');
    });
</script>
</x-guest-layout>