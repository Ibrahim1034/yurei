<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'profile_picture',
        'membership_number',
        'registration_date',
        'expiration_date',
        'is_active',
        'role',
        'user_type', // 'member' or 'friend'
        'county',
        'constituency',
        'ward',
        'institution',
        'graduation_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'registration_date' => 'date',
            'expiration_date' => 'date',
            'is_active' => 'boolean',
            'role' => 'integer',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected $attributes = [
        'is_active' => false,
        'role' => 0, // Default role is 0 (regular user)
        'membership_number' => null,
        'registration_date' => null,
        'expiration_date' => null,
        'profile_picture' => null,
        'phone_number' => null,
        'user_type' => 'member',
    ];

    /**
     * Boot method to set registration date when creating a user
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->registration_date)) {
                $user->registration_date = now();
            }
        });
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 0;
    }

    /**
     * Check if user is a member
     */
    public function isMember()
    {
        return $this->user_type === 'member';
    }

    /**
     * Check if user is a friend
     */
    public function isFriend()
    {
        return $this->user_type === 'friend';
    }

    /**
     * Update user's profile picture
     */
    public function updateProfilePicture($file)
    {
        // Delete old profile picture if exists
        if ($this->profile_picture && Storage::disk('public')->exists($this->profile_picture)) {
            Storage::disk('public')->delete($this->profile_picture);
        }

        // Store new profile picture
        $path = $file->store('profile_pictures', 'public');
        $this->profile_picture = $path;
        $this->save();

        return $path;
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return Storage::disk('public')->url($this->profile_picture);
        }
        
        return asset('storage/web_pics/yurei-036.jpeg'); // Default avatar
    }

    public function membershipCard()
    {
        return $this->hasOne(MembershipCard::class);
    }

     // Add this relationship method
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Generate a membership number for the user
     */
    public function generateMembershipNumber()
    {
        if (!$this->membership_number) {
            $prefix = $this->isFriend() ? 'YUREI-F' : 'YUREI-M';
            $this->membership_number = $prefix . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
            $this->save();
        }
        return $this->membership_number;
    }

    /**
     * Get the displayable user type
     */
    public function getUserTypeDisplayAttribute()
    {
        return $this->isMember() ? 'Member' : 'Friend of YUREI';
    }
}