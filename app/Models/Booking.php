<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

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
        'scheduled_date' => 'date',
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
    ];

    protected $appends = ['formatted_schedule'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function assetType()
    {
        return $this->belongsTo(\App\Models\AssetType::class);
    }

    public function assetDetail()
    {
        return $this->belongsTo(\App\Models\AssetDetail::class);
    }

    public function bookedGuests()
    {
        return $this->hasMany(BookedGuest::class);
    }

    public function approver1()
    {
        return $this->belongsTo(User::class, 'approver_1_id');
    }

    public function approver2()
    {
        return $this->belongsTo(User::class, 'approver_2_id');
    }

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     */

    public function getFormattedScheduleAttribute()
    {
        $date = $this->scheduled_date
            ? $this->scheduled_date->format('l, F j, Y')
            : 'N/A';

        $from = $this->time_from
            ? Carbon::parse($this->time_from)->format('g:i A')
            : 'N/A';

        $to = $this->time_to
            ? Carbon::parse($this->time_to)->format('g:i A')
            : 'N/A';

        return "$date — $from to $to";
    }
}
