<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetDetail extends Model
{
    protected $fillable = [
        'asset_type_id',
        'asset_name',
        'location',
        'brand',
        'model',
        'color',
        'plate_number',
        'number_of_seats',
    ];

    protected $casts = [
        'number_of_seats' => 'integer',
    ];

    /**
     * Get the asset type that owns the asset detail.
     */
    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    /**
     * Get the asset files for the asset detail.
     */
    public function assetFiles(): HasMany
    {
        return $this->hasMany(AssetFile::class);
    }

    /**
     * Get the bookings for the asset detail.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the vehicle driver assignments for the asset detail.
     */
    public function vehicleDriverAssignments(): HasMany
    {
        return $this->hasMany(VehicleDriverAssignment::class);
    }
}
