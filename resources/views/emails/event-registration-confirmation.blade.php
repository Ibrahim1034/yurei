<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Registration Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #196762; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; }
        .event-details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #196762; }
        .invitation-code { background-color: #f0f0f0; padding: 10px; border-radius: 4px; font-family: monospace; font-weight: bold; color: #196762; text-align: center; font-size: 18px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>YUREI Kenya</h1>
            <h2>Event Registration Confirmed!</h2>
        </div>
        
        <div class="content">
            @if($is_guest)
                <p>Hello <strong>{{ $guest_name }}</strong>,</p>
            @else
                <p>Hello <strong>{{ $user->name }}</strong>,</p>
            @endif
            
            <p>Your registration for the following event has been confirmed:</p>
            
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                <p><strong>Date:</strong> {{ $event->event_date->format('F j, Y \a\t h:i A') }}</p>
                <p><strong>Venue:</strong> {{ $event->venue }}</p>
                <p><strong>Registration ID:</strong> EVENT-{{ $registration->id }}</p>
                
                <!-- INVITATION CODE -->
                <p><strong>Your Invitation Code:</strong></p>
                <div class="invitation-code">
                    {{ $registration->invitation_code }}
                </div>
                <!-- END INVITATION CODE -->
                
                @if($event->is_paid)
                <p><strong>Amount Paid:</strong> KES {{ number_format($registration->amount_paid, 2) }}</p>
                @else
                <p><strong>Event Type:</strong> Free Event</p>
                @endif
            </div>
            
            <p><strong>Important:</strong> Please bring this invitation code with you to the event for quick check-in and verification.</p>
            
            <p>We look forward to seeing you at the event!</p>
            
            <p><strong>Event Description:</strong><br>
            {{ $event->description }}</p>
            
            <p>If you have any questions, please contact us at youthrescueyurei@gmail.com</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Youth Rescue and Empowerment Initiative. All rights reserved.</p>
            <p>Contact: youthrescueyurei@gmail.com | 0794142078</p>
        </div>
    </div>
</body>
</html>