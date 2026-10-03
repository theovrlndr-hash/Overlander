<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'destination_id', 'booking_id', 'user_id',
        'reviewer_name', 'reviewer_avatar', 'rating', 'comment', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Uploaded files live on disk, so they are removed together with the review.
        static::deleting(function (Review $review) {
            foreach ($review->photos as $photo) {
                if (str_starts_with($photo->path, 'uploads/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
                }
            }
        });
    }

    public function photos()
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
