<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, $user, $customMessage = null)
    {
        $this->event = $event;
        $this->user = $user;
        $this->customMessage = $customMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $registerUrl = url("/events/{$this->event->id}/register");
        
        // Determine greeting based on whether user is a member or external
        $greetingName = $this->user->name ?? 'Guest';
        if (isset($this->user->is_external) && $this->user->is_external) {
            $greetingName = 'there'; // Generic greeting for external users
        }
        
        return $this->subject('You\'re Invited: ' . $this->event->title)
                    ->view('emails.event-invitation')
                    ->with([
                        'event' => $this->event,
                        'user' => $this->user,
                        'customMessage' => $this->customMessage,
                        'registerUrl' => $registerUrl,
                        'greetingName' => $greetingName
                    ]);
    }
}