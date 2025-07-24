<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'asset_type_id',
        'asset_detail_id',
        'user_id',
        'purpose',
        'no_of_seats',
        'destination',
        'scheduled_date',
        'time_from',
        'time_to',
        'notes',
        'status',
    ];

    protected $casts = [
        'no_of_seats' => 'integer',
        'scheduled_date' => 'date',
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
    ];

    /**
     * Get the asset type that owns the booking.
     */
    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    /**
     * Get the asset detail that owns the booking.
     */
    public function assetDetail(): BelongsTo
    {
        return $this->belongsTo(AssetDetail::class);
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booked guests for the booking.
     */
    public function bookedGuests(): HasMany
    {
        return $this->hasMany(BookedGuest::class);
    }

    /**
     * Get the vehicle driver assignments for the booking.
     */
    public function vehicleDriverAssignments(): HasMany
    {
        return $this->hasMany(VehicleDriverAssignment::class);
    }

    /**
     * Get the approval logs for the booking.
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class);
    }
}
