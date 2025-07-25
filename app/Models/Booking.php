<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'asset_type_id',
        'asset_name',
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
        'scheduled_date' => 'date',
        'time_from' => 'string',
        'time_to' => 'string',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guests()
    {
        return $this->hasMany(BookedGuest::class);
    }

    public function vehicleAssignment()
    {
        return $this->hasOne(VehicleDriverAssignment::class);
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class);
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }
}
