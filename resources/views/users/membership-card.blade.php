<!-- resources/views/admin/users/membership-card.blade.php -->
<x-app-layout>


     <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person-badge me-2"></i>Membership Card: {{ $user->name }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to User
                </a>
                <button onclick="downloadAsPDF()" class="btn btn-success">
                    <i class="bi bi-download me-1"></i>Download PDF
                </button>
            </div>
        </div>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Membership Card Design - Same as User View -->
                    <div class="membership-card" id="membership-card">
                        <div class="watermark">
                            <img src="{{ asset('storage/web_pics/yurei-036.png') }}" alt="YUREI Watermark">
                        </div>
                        
                        <div class="card-header">
                            <img src="{{ asset('storage/web_pics/yurei-036.jpeg') }}" 
                                 class="logo" 
                                 alt="YUREI Logo">
                            <div class="header-text">
                                <div>YUREI KENYA</div>
                                <div>Membership Card</div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="photo-section">
                                @if($user->membershipCard->card_photo_path && Storage::disk('public')->exists($user->membershipCard->card_photo_path))
                                    <img src="{{ $user->membershipCard->card_photo_url }}" 
                                         class="member-photo" 
                                         alt="Member Photo">
                                @else
                                    <div class="member-photo no-photo">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="info-section">
                                <div class="member-name">{{ $user->name }}</div>
                                <div class="member-email">{{ $user->email }}</div>
                                <div class="membership-number">{{ $user->membershipCard->membership_number }}</div>
                                <div class="dates">
                                    <div><strong>Registered:</strong> {{ $user->membershipCard->registration_date->format('F d, Y') }}</div>
                                    <div><strong>Expires:</strong> {{ $user->membershipCard->expiration_date->format('F d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="organization-name">Youth Rescue and Empowerment Initiative</div>
                        </div>
                    </div>

                    <!-- Card Status -->
                    <div class="dashboard-card mt-4" style="height: 20%">
                        <h5 class="fw-bold mb-3">Card Status</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <span class="badge {{ $user->membershipCard->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->membershipCard->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Validity:</strong>
                                <span class="badge {{ $user->membershipCard->is_expired ? 'bg-danger' : 'bg-success' }}">
                                    {{ $user->membershipCard->is_expired ? 'Expired' : 'Valid' }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Membership Number:</strong> {{ $user->membershipCard->membership_number }}
                            </div>
                            <div class="col-md-6">
                                <strong>User Status:</strong>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>User Role:</strong>
                                <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-info' }}">
                                    {{ $user->isAdmin() ? 'Administrator' : 'Regular User' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
        
        .membership-card {
            width: 100%;
            max-width: 280mm;
            height: 180mm;
            background: linear-gradient(135deg, #196762 0%, #1a7a74 100%);
            border-radius: 20px;
            color: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
            margin: 0 auto 30px auto;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(0deg);
            pointer-events: none;
            z-index: 0;
            opacity: 0.3;
        }
        
        .watermark img {
            width: 400px;
            height: 400px;
            object-fit: contain;
            filter: none;
            border-radius: 0;
        }
        
        .card-header {
            padding: 20px 30px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid rgba(255,255,255,0.4);
            object-fit: cover;
        }
        
        .header-text {
            text-align: right;
        }

        .header-text div:first-child {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-text div:last-child {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 40px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            height: calc(100% - 160px);
        }
        
        .photo-section {
            flex: 0 0 45%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .member-photo {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.4);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }
        
        .no-photo {
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid rgba(255,255,255,0.3);
        }
        
        .no-photo i {
            font-size: 4rem;
            color: rgba(255,255,255,0.5);
        }
        
        .info-section {
            flex: 0 0 50%;
            text-align: center;
            padding: 20px;
        }
        
        .member-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .member-email {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 25px;
            color: rgba(255,255,255,0.9);
        }
        
        .membership-number {
            font-size: 24px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 12px 24px;
            border-radius: 10px;
            margin-bottom: 25px;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            display: inline-block;
        }
        
        .dates {
            font-size: 18px;
            opacity: 0.95;
            color: rgba(255,255,255,0.9);
        }
        
        .dates div {
            margin-bottom: 8px;
        }
        
        .card-footer {
            padding: 5px 30px;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(15px);
            text-align: center;
            font-size: 18px;
            color: rgba(255,255,255,0.9);
            border-top: 2px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 1;
        }

        .organization-name {
            font-weight: bold;
            font-size: 22px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 280mm;
            margin: 0 auto;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            .membership-card {
                box-shadow: none !important;
                margin: 0 !important;
                height: 180mm !important;
            }
            .dashboard-card {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .membership-card {
                height: auto;
                min-height: 180mm;
            }
            
            .card-body {
                flex-direction: column;
                height: auto;
                padding: 20px;
            }
            
            .photo-section, .info-section {
                flex: 1;
                width: 100%;
            }
            
            .member-name {
                font-size: 24px;
            }
            
            .member-email {
                font-size: 16px;
            }
            
            .membership-number {
                font-size: 18px;
            }
            
            .dates {
                font-size: 14px;
            }
            
            .watermark img {
                width: 300px;
                height: 300px;
            }
            
            .header-text div:first-child {
                font-size: 20px;
            }
            
            .header-text div:last-child {
                font-size: 14px;
            }
            
            .logo {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .watermark img {
                width: 200px;
                height: 200px;
            }
        }
    </style>

    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        // Download as PDF function
        function downloadAsPDF() {
            // Show loading state
            const downloadBtn = event.target;
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Generating PDF...';
            downloadBtn.disabled = true;

            // Get the membership card element
            const element = document.getElementById('membership-card');
            
            // PDF options
            const options = {
                margin: 10,
                filename: 'YUREI-Membership-Card-{{ $user->membershipCard->membership_number }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: true,
                    width: element.scrollWidth,
                    height: element.scrollHeight
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'landscape' 
                }
            };

            // Generate PDF
            html2pdf()
                .set(options)
                .from(element)
                .save()
                .then(() => {
                    // Restore button state
                    downloadBtn.innerHTML = originalText;
                    downloadBtn.disabled = false;
                })
                .catch((error) => {
                    console.error('PDF generation failed:', error);
                    // Fallback to print if PDF generation fails
                    window.print();
                    // Restore button state
                    downloadBtn.innerHTML = originalText;
                    downloadBtn.disabled = false;
                });
        }

        // Print functionality
        function printMembershipCard() {
            window.print();
        }

        // Alternative: Direct PDF download using server-side generation
        function downloadServerPDF() {
            window.open('{{ route("membership-card.download") }}?user_id={{ $user->id }}', '_blank');
        }
    </script>
</x-app-layout>