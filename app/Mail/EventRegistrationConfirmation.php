<?php
// app/Mail/EventRegistrationConfirmation.php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Event Registration Confirmation - ' . $this->registration->event->title)
                    ->view('emails.event-registration-confirmation')
                    ->with([
                        'registration' => $this->registration,
                        'event' => $this->registration->event,
                        'user' => $this->registration->user,
                        'is_guest' => $this->registration->is_guest,
                        'guest_name' => $this->registration->guest_name,
                        'guest_email' => $this->registration->guest_email,
                        'invitation_code' => $this->registration->invitation_code
                    ]);
    }
}