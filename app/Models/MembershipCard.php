<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MembershipCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_number',
        'card_photo_path',
        'registration_date',
        'expiration_date',
        'is_active',
        'county',
        'constituency',
        'ward',
        'institution',
        'graduation_status',
        'user_type'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiration_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the membership card.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get card photo URL
     */
    public function getCardPhotoUrlAttribute()
    {
        if ($this->card_photo_path) {
            return Storage::disk('public')->url($this->card_photo_path);
        }
        
        return asset('storage/web_pics/default-passport.jpg');
    }

    /**
     * Generate unique membership number
     */
    public static function generateMembershipNumber($userType = 'member')
    {
        do {
            $prefix = $userType === 'friend' ? 'YUREI-F' : 'YUREI-M';
            $number = $prefix . '-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('membership_number', $number)->exists());

        return $number;
    }

    /**
     * Check if card is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiration_date->isPast();
    }

    /**
     * Get user type display name
     */
    public function getUserTypeDisplayAttribute()
    {
        return $this->user_type === 'friend' ? 'Friend of YUREI' : 'Member';
    }

    /**
     * Get graduation status display name
     */
    public function getGraduationStatusDisplayAttribute()
    {
        return $this->graduation_status === 'studying' ? 'Currently Studying' : 'Graduated';
    }

    /**
     * Scope active cards
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expiration_date', '>', now());
    }
}