<?php
use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use Illuminate\Http\Request;

Route::get('/bookings', function (Request $request) {
    $query = Booking::with(['user', 'assetType']);

    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->asset_type_id) {
        $query->where('asset_type_id', $request->asset_type_id);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $bookings = $query->get();

    $events = $bookings->map(function ($booking) {
        return [
            'title' => "{$booking->purpose} - {$booking->assetType->name}",
            'start' => $booking->scheduled_date->format('Y-m-d') . 'T' . $booking->time_from,
            'end' => $booking->scheduled_date->format('Y-m-d') . 'T' . $booking->time_to,
            'backgroundColor' => match ($booking->status) {
                'approved' => 'green',
                'rejected' => 'red',
                default => 'orange',
            },
            'extendedProps' => [
                'user' => $booking->user->first_name . ' ' . $booking->user->last_name,
                'asset_type' => $booking->assetType->name,
                'destination' => $booking->destination,
                'status' => $booking->status,
            ]
        ];
    });

    return response()->json($events);
});

