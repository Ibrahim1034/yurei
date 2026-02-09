<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program Registration Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #196762; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; }
        .program-details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #196762; }
        .invitation-code { background-color: #f0f0f0; padding: 10px; border-radius: 4px; font-family: monospace; font-weight: bold; color: #196762; text-align: center; font-size: 18px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>YUREI Kenya</h1>
            <h2>Program Registration Confirmed!</h2>
        </div>
        
        <div class="content">
            @if($is_guest)
                <p>Hello <strong>{{ $guest_name }}</strong>,</p>
            @else
                <p>Hello <strong>{{ $user->name }}</strong>,</p>
            @endif
            
            <p>Thank you for registering for our program. Your registration has been confirmed:</p>
            
            <div class="program-details">
                <h3>{{ $program->title }}</h3>
                <p><strong>Duration:</strong> {{ $program->duration }}</p>
                <p><strong>Start Date:</strong> {{ $program->start_date->format('F j, Y \a\t h:i A') }}</p>
                <p><strong>End Date:</strong> {{ $program->end_date->format('F j, Y \a\t h:i A') }}</p>
                <p><strong>Venue:</strong> {{ $program->venue }}</p>
                <p><strong>Registration ID:</strong> PROGRAM-{{ $registration->id }}</p>
                
                <p><strong>Your Invitation Code:</strong></p>
                <div class="invitation-code">
                    {{ $registration->invitation_code }}
                </div>
                
                @if($program->is_paid)
                <p><strong>Amount Paid:</strong> KES {{ number_format($registration->amount_paid, 2) }}</p>
                @else
                <p><strong>Program Type:</strong> Free Program</p>
                @endif
            </div>
            
            <p><strong>Important:</strong> Please bring this invitation code with you to the program for check-in.</p>
            
            <p>We look forward to having you in this program!</p>
            
            <p><strong>Program Description:</strong><br>
            {{ $program->description }}</p>
            
            <p>If you have any questions, please contact us at youthrescueyurei@gmail.com</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Youth Rescue and Empowerment Initiative. All rights reserved.</p>
            <p>Contact: youthrescueyurei@gmail.com | 0794142078</p>
        </div>
    </div>
</body>
</html>