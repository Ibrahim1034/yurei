<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .membership-card {
            width: 85mm;
            height: 54mm;
            background: linear-gradient(135deg, #196762 0%, #1a7a74 100%);
            border-radius: 12px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
        }
        
        .card-header {
            padding: 8px 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .logo {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .card-body {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .member-photo {
            width: 32mm;
            height: 32mm;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 12px;
            border: 3px solid rgba(255,255,255,0.4);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .member-info {
            flex: 1;
        }
        
        .member-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .member-email {
            font-size: 9px;
            opacity: 0.95;
            margin-bottom: 6px;
            color: rgba(255,255,255,0.9);
        }
        
        .membership-number {
            font-size: 10px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .user-type-badge {
            font-size: 8px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
            margin-bottom: 4px;
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

        .location-info, .education-info {
            font-size: 7px;
            margin-bottom: 2px;
            color: rgba(255,255,255,0.8);
        }
        
        .dates {
            font-size: 9px;
            opacity: 0.95;
            color: rgba(255,255,255,0.9);
        }
        
        .dates div {
            margin-bottom: 2px;
        }
        
        .card-footer {
            padding: 6px 15px;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(10px);
            text-align: center;
            font-size: 8px;
            color: rgba(255,255,255,0.9);
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .organization-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .card-type {
            font-size: 7px;
            opacity: 0.8;
        }

        .header-text {
            text-align: right;
        }

        .header-text div:first-child {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header-text div:last-child {
            font-size: 7px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="membership-card">
        <div class="card-header">
            <img src="{{ storage_path('app/public/web_pics/yurei-036.jpeg') }}" class="logo" alt="YUREI Logo">
            <div class="header-text">
                <div>YUREI KENYA</div>
                <div>Membership Card</div>
            </div>
        </div>
        
        <div class="card-body">
            @if(file_exists(storage_path('app/public/' . $membershipCard->card_photo_path)))
                <img src="{{ storage_path('app/public/' . $membershipCard->card_photo_path) }}" class="member-photo" alt="Member Photo">
            @else
                <div class="member-photo" style="background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255,255,255,0.3);">
                    <span style="font-size: 8px; color: rgba(255,255,255,0.7);">No Photo</span>
                </div>
            @endif
            <div class="member-info">
                <div class="member-name">{{ $membershipCard->user->name }}</div>
                <div class="member-email">{{ $membershipCard->user->email }}</div>
                
                <div class="user-type-badge {{ $membershipCard->user_type === 'friend' ? 'friend-badge' : 'member-badge' }}">
                    {{ $membershipCard->user_type_display }}
                </div>
                
                <div class="membership-number">{{ $membershipCard->membership_number }}</div>
                
                <!-- Only show location details for members -->
                @if($membershipCard->user_type === 'member')
                    @if($membershipCard->county)
                    <div class="location-info">
                        <strong>County:</strong> {{ $membershipCard->county }}
                    </div>
                    @endif
                    @if($membershipCard->institution)
                    <div class="education-info">
                        <strong>Institution:</strong> {{ $membershipCard->institution }}
                    </div>
                    @endif
                @else
                    <div class="location-info">
                        Friend of YUREI
                    </div>
                @endif
                
                <div class="dates">
                    <div>Registered: {{ $membershipCard->registration_date->format('M d, Y') }}</div>
                    <div>Expires: {{ $membershipCard->expiration_date->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="organization-name">Youth Rescue and Empowerment Initiative</div>
        </div>
    </div>
</body>
</html>