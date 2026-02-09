<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'venue',
        'image',
        'created_by',
        'is_paid',
        'registration_fee',
        'max_participants',
        'current_participants',
        'registration_deadline'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_paid' => 'boolean',
        'registration_fee' => 'decimal:2',
        'max_participants' => 'integer',
        'current_participants' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isRegistrationOpen(): bool
    {
        // Check if registration deadline is set and not passed
        $deadlineValid = $this->registration_deadline 
            ? now()->lte($this->registration_deadline) 
            : true; // If no deadline, registration is always open
        
        // Check if there are available spots
        $spotsAvailable = $this->max_participants == 0 
            ? true // Unlimited participants
            : $this->current_participants < $this->max_participants;
        
        return $deadlineValid && $spotsAvailable;
    }

    // Helper method to check if event has registration deadline
    public function hasRegistrationDeadline(): bool
    {
        return !is_null($this->registration_deadline);
    }

    /**
     * Check if event is within attendance marking window (5 hours before event)
     */
    public function isWithinAttendanceWindow(): bool
    {
        $attendanceStart = $this->event_date->copy()->subHours(5);
        $attendanceEnd = $this->event_date->copy()->addHours(2); // Allow 2 hours after event start
        
        return now()->between($attendanceStart, $attendanceEnd);
    }

    /**
     * Check if attendance marking has started
     */
    public function hasAttendanceStarted(): bool
    {
        return now()->gte($this->event_date->copy()->subHours(5));
    }

    /**
     * Check if attendance marking has ended
     */
    public function hasAttendanceEnded(): bool
    {
        return now()->gt($this->event_date->copy()->addHours(2));
    }

    /**
     * Get attendance statistics
     */
    public function getAttendanceStats(): array
    {
        $totalRegistrations = $this->registrations()->withInvitations()->count();
        $attendedCount = $this->registrations()->attended()->count();
        $attendanceRate = $totalRegistrations > 0 ? round(($attendedCount / $totalRegistrations) * 100) : 0;

        return [
            'total_registrations' => $totalRegistrations,
            'attended_count' => $attendedCount,
            'attendance_rate' => $attendanceRate,
            'not_attended_count' => $totalRegistrations - $attendedCount
        ];
    }
}