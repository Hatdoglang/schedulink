<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

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
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function assetType()
    {
        return $this->belongsTo(\App\Models\AssetType::class);

    }
}
