<x-app-layout>
    <x-slot name="header">
       
    </x-slot>

    <div class="py-4">
         <div class="d-flex justify-content-between align-items-center my-2" style="margin-left: 10%; margin-right: 15%">
            <h2 class="h4 fw-bold text-gray-800 mb-0">
                <i class="bi bi-person-badge me-2"></i>My Membership Card
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('membership-card.print') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i>Print
                </a>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Membership Card Design - Matching Print Version 100% -->
                    <div class="membership-card">
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
                                <div class="user-type-badge {{ $membershipCard->user_type === 'friend' ? 'friend-badge' : 'member-badge' }}">
                                    {{ $membershipCard->user_type_display }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="photo-section">
                                @if($membershipCard->card_photo_path && Storage::disk('public')->exists($membershipCard->card_photo_path))
                                    <img src="{{ $membershipCard->card_photo_url }}" 
                                         class="member-photo" 
                                         alt="Member Photo">
                                @else
                                    <div class="member-photo no-photo">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="info-section">
                                <div class="member-name">{{ $membershipCard->user->name }}</div>
                                <div class="member-email">{{ $membershipCard->user->email }}</div>
                                <div class="member-phone">{{ $membershipCard->user->phone_number }}</div>
                                
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

                    <!-- Card Status -->
                    <div class="dashboard-card mt-4">
                        <h5 class="fw-bold mb-3">Card Status</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Status:</strong>
                                <span class="badge {{ $membershipCard->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $membershipCard->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <strong>Validity:</strong>
                                <span class="badge {{ $membershipCard->is_expired ? 'bg-danger' : 'bg-success' }}">
                                    {{ $membershipCard->is_expired ? 'Expired' : 'Valid' }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <strong>Type:</strong>
                                <span class="badge {{ $membershipCard->user_type === 'friend' ? 'bg-info' : 'bg-primary' }}">
                                    {{ $membershipCard->user_type_display }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Membership Number:</strong> {{ $membershipCard->membership_number }}
                            </div>
                        </div>
                        @if($membershipCard->user_type === 'member')
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <strong>County:</strong> {{ $membershipCard->county ?? 'N/A' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Constituency:</strong> {{ $membershipCard->constituency ?? 'N/A' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Ward:</strong> {{ $membershipCard->ward ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Institution:</strong> {{ $membershipCard->institution ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Graduation Status:</strong> {{ $membershipCard->graduation_status_display ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
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

        .header-text div:nth-child(2) {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .user-type-badge {
            font-size: 14px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .member-badge {
            background: rgba(255,255,255,0.3);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .friend-badge {
            background: rgba(40, 167, 69, 0.3);
            border: 1px solid rgba(40, 167, 69, 0.5);
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
            position: relative;
            z-index: 1;
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
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 280mm;
            margin: 0 auto;
            height: 210px;
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
            
            .member-email, .member-phone {
                font-size: 14px;
            }
            
            .membership-number {
                font-size: 16px;
            }
            
            .dates {
                font-size: 14px;
            }
            
            .location-row, .education-row {
                flex-direction: column;
                text-align: center;
            }
            
            .watermark img {
                width: 300px;
                height: 300px;
            }
            
            .header-text div:first-child {
                font-size: 20px;
            }
            
            .header-text div:nth-child(2) {
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
</x-app-layout>