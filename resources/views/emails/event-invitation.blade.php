
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Invitation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #196762; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; }
        .event-details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #196762; }
        .btn-primary { background: #196762; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>YUREI Kenya</h1>
            <h2>You're Invited!</h2>
        </div>
        
        <div class="content">
            @if(isset($user->is_external) && $user->is_external)
                <p>Hello there,</p>
                <p>You have been invited to attend the following event organized by YUREI Kenya:</p>
            @else
                <p>Hello <strong>{{ $user->name }}</strong>,</p>
                <p>You have been invited to attend the following event:</p>
            @endif
            
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                <p><strong>Date:</strong> {{ $event->event_date->format('F j, Y \a\t h:i A') }}</p>
                <p><strong>Venue:</strong> {{ $event->venue }}</p>
                
                @if($event->is_paid)
                <p><strong>Registration Fee:</strong> KES {{ number_format($event->registration_fee, 2) }}</p>
                @else
                <p><strong>Event Type:</strong> Free Event</p>
                @endif
            </div>

            @if($customMessage)
            <div style="background: #e8f4fd; padding: 15px; border-radius: 4px; margin: 15px 0;">
                <strong>Special Message:</strong>
                <p style="margin: 5px 0 0 0;">{{ $customMessage }}</p>
            </div>
            @endif
            
            <p><strong>Event Description:</strong><br>
            {{ $event->description }}</p>
            
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $registerUrl }}" class="btn-primary">
                    Register for this Event
                </a>
            </div>
            
            <p><strong>Important:</strong> Please register using the button above to secure your spot. After registration, you'll receive a unique invitation code for event entry.</p>
            
            @if(isset($user->is_external) && $user->is_external)
            <p><strong>Note:</strong> You don't need to be a YUREI member to attend this event. Everyone is welcome!</p>
            @endif
            
            <p>If you have any questions, please contact us at youthrescueyurei@gmail.com</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Youth Rescue and Empowerment Initiative. All rights reserved.</p>
            <p>Contact: youthrescueyurei@gmail.com | 0794142078</p>
        </div>
    </div>
</body>
</html>