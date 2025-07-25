<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookedGuest extends Model
{
    protected $fillable = [
        'booking_id',
        'email',
    ];

    /**
     * Get the booking that owns the booked guest.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
