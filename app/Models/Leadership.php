<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Leadership extends Model
{
    use HasFactory;

    // Specify the table name explicitly
    protected $table = 'leadership';

    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'bio',
        'image_path',
        'order',
        'is_active',
        'social_facebook',
        'social_twitter',
        'social_linkedin',
        'social_instagram'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }
        
        return asset('storage/web_pics/default-leader.jpg');
    }

    /**
     * Scope active leaders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by position order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}