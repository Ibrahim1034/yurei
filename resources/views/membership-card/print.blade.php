<!DOCTYPE html>
<html>
<head>
    <title>YUREI Membership Card - {{ $membershipCard->user->name }}</title>
    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .membership-card {
                box-shadow: none !important;
                margin: 0 !important;
            }
        }
        
        .membership-card {
            width: 280mm;
            height: 180mm;
            background: linear-gradient(135deg, #196762 0%, #1a7a74 100%);
            border-radius: 20px;
            color: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
            margin-bottom: 30px;
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
            border-radius: 70%;
        }
        
        .card-header {
            padding: 20px 30px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }
        
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid rgba(255,255,255,0.4);
            object-fit: cover;
        }
        
        .card-body {
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            height: calc(100% - 160px);
        }
        
        .photo-section {
            flex: 0 0 40%;
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
        
        .info-section {
            flex: 0 0 55%;
            text-align: left;
            padding: 20px;
        }
        
        .member-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            text-align: center;
        }
        
        .member-email, .member-phone {
            font-size: 16px;
            opacity: 0.95;
            margin-bottom: 8px;
            color: rgba(255,255,255,0.9);
            text-align: center;
        }
        
        .membership-number {
            font-size: 20px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 10px;
            margin: 15px 0;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .user-type-badge {
            font-size: 14px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            display: inline-block;
        }

        .member-badge {
            background: rgba(255,255,255,0.3);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .friend-badge {
            background: rgba(40, 167, 69, 0.3);
            border: 1px solid rgba(40, 167, 69, 0.5);
        }

        .location-details, .education-details {
            margin: 15px 0;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .location-row, .education-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .location-label, .education-label {
            font-weight: bold;
            color: rgba(255,255,255,0.9);
        }

        .location-value, .education-value {
            color: rgba(255,255,255,0.8);
        }

        .friend-message {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.2);
            font-style: italic;
        }
        
        .dates {
            font-size: 16px;
            opacity: 0.95;
            color: rgba(255,255,255,0.9);
            margin-top: 15px;
            text-align: center;
        }
        
        .dates div {
            margin-bottom: 8px;
        }
        
        .card-footer {
            padding: 15px 30px;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(15px);
            text-align: center;
            font-size: 16px;
            color: rgba(255,255,255,0.9);
            border-top: 2px solid rgba(255,255,255,0.1);
        }

        .organization-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .contact-info {
            font-size: 12px;
            opacity: 0.8;
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

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 5px;
        }

        .btn-primary {
            background: #196762;
            color: white;
        }

        .btn-primary:hover {
            background: #1a7a74;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: white;
            font-size: 18px;
            flex-direction: column;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #196762;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div>Generating PDF...</div>
    </div>

    <div class="print-controls no-print">
        <button onclick="downloadAsPDF()" class="btn btn-primary">
            <i class="bi bi-download"></i> Download PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="bi bi-x"></i> Close
        </button>
    </div>

    <div class="membership-card" id="membershipCard">
        <div class="watermark">
            <img src="{{ asset('storage/web_pics/yurei-036.png') }}" alt="YUREI Watermark">
        </div>
        
        <div class="card-header">
            <img src="{{ asset('storage/web_pics/yurei-036.jpeg') }}" class="logo" alt="YUREI Logo">
            <div class="header-text">
                <div>YUREI KENYA</div>
                <div>Membership Card</div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="photo-section">
                @if($membershipCard->card_photo_path && Storage::disk('public')->exists($membershipCard->card_photo_path))
                    <img src="{{ $membershipCard->card_photo_url }}" class="member-photo" alt="Member Photo">
                @else
                    <div class="member-photo" style="background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; border: 5px solid rgba(255,255,255,0.3);">
                        <i class="bi bi-person" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                    </div>
                @endif
            </div>
            
            <div class="info-section">
                <div class="member-name">{{ $membershipCard->user->name }}</div>
                <div class="member-email">{{ $membershipCard->user->email }}</div>
                <div class="member-phone">{{ $membershipCard->user->phone_number }}</div>
                
                <div class="user-type-badge {{ $membershipCard->user_type === 'friend' ? 'friend-badge' : 'member-badge' }}">
                    {{ $membershipCard->user_type_display }}
                </div>
                
                <div class="membership-number">{{ $membershipCard->membership_number }}</div>
                
                <!-- Only show location details for members -->
                @if($membershipCard->user_type === 'member')
                <div class="location-details">
                    <div class="location-row">
                        <span class="location-label">County:</span>
                        <span class="location-value">{{ $membershipCard->county ?? 'N/A' }}</span>
                    </div>
                    <div class="location-row">
                        <span class="location-label">Constituency:</span>
                        <span class="location-value">{{ $membershipCard->constituency ?? 'N/A' }}</span>
                    </div>
                    <div class="location-row">
                        <span class="location-label">Ward:</span>
                        <span class="location-value">{{ $membershipCard->ward ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="education-details">
                    <div class="education-row">
                        <span class="education-label">Institution:</span>
                        <span class="education-value">{{ $membershipCard->institution ?? 'N/A' }}</span>
                    </div>
                    <div class="education-row">
                        <span class="education-label">Status:</span>
                        <span class="education-value">{{ $membershipCard->graduation_status_display ?? 'N/A' }}</span>
                    </div>
                </div>
                @else
                <div class="friend-message">
                    <p>Thank you for being a Friend of YUREI!</p>
                </div>
                @endif
                
                <div class="dates">
                    <div><strong>Registered:</strong> {{ $membershipCard->registration_date->format('F d, Y') }}</div>
                    <div><strong>Expires:</strong> {{ $membershipCard->expiration_date->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="organization-name">Youth Rescue and Empowerment Initiative</div>
            <div class="contact-info">Email: info@yurei.org | Phone: +254 XXX XXX XXX</div>
        </div>
    </div>

    <script>
        function downloadAsPDF() {
            const element = document.getElementById('membershipCard');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            // PDF options
            const options = {
                margin: 0,
                filename: 'YUREI_Membership_Card_{{ $membershipCard->user->name }}.pdf',
                image: { 
                    type: 'jpeg', 
                    quality: 1.0 
                },
                html2canvas: { 
                    scale: 3, // Higher scale for better quality
                    useCORS: true,
                    logging: false,
                    backgroundColor: null // Preserve transparent background
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: [285, 180], // Match card dimensions
                    orientation: 'landscape'
                }
            };

            // Generate PDF
            html2pdf()
                .set(options)
                .from(element)
                .save()
                .then(() => {
                    // Hide loading overlay when done
                    loadingOverlay.style.display = 'none';
                })
                .catch((error) => {
                    console.error('PDF generation failed:', error);
                    loadingOverlay.style.display = 'none';
                    alert('Failed to generate PDF. Please try again.');
                });
        }

        function printCard() {
            window.print();
        }

        // Auto-print when page loads (optional)
        window.onload = function() {
            // You can enable auto-print if desired
            // setTimeout(printCard, 1000);
        };

        // Ensure images are loaded before PDF generation
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            let loadedImages = 0;
            const totalImages = images.length;

            images.forEach(img => {
                if (img.complete) {
                    loadedImages++;
                } else {
                    img.addEventListener('load', () => {
                        loadedImages++;
                    });
                    img.addEventListener('error', () => {
                        loadedImages++; // Count even if error to avoid blocking
                    });
                }
            });
        });
    </script>
</body>
</html>