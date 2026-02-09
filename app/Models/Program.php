<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
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
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
        return $this->hasMany(ProgramRegistration::class);
    }

    public function isRegistrationOpen(): bool
    {
        $deadlineValid = $this->registration_deadline 
            ? now()->lte($this->registration_deadline) 
            : true;
            
        $spotsAvailable = $this->max_participants == 0 
            ? true
            : $this->current_participants < $this->max_participants;
        
        return $deadlineValid && $spotsAvailable;
    }

    public function hasRegistrationDeadline(): bool
    {
        return !is_null($this->registration_deadline);
    }

    public function getDurationAttribute(): string
    {
        $days = round($this->start_date->diffInDays($this->end_date));
        $days = max(1, $days); // Ensure at least 1 day
        
        return $days . ' day' . ($days > 1 ? 's' : '');
    }

    public function isOngoing(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return now()->lt($this->start_date);
    }

    public function isCompleted(): bool
    {
        return now()->gt($this->end_date);
    }

    /**
     * Check if attendance can be marked for this program
     * Active 5 hours before the event starts until the event ends
     */
    public function canMarkAttendance(): bool
    {
        $attendanceStartTime = $this->start_date->subHours(5);
        return now()->between($attendanceStartTime, $this->end_date);
    }

    /**
     * Get confirmed registrations with invitation codes
     */
    public function getConfirmedRegistrations()
    {
        return $this->registrations()
            ->where(function($query) {
                $query->where('status', 'confirmed')
                      ->orWhere('status', 'attended');
            })
            ->whereNotNull('invitation_code')
            ->get();
    }

    /**
     * Get attended registrations count
     */
    public function getAttendedCountAttribute(): int
    {
        return $this->registrations()->where('status', 'attended')->count();
    }
}