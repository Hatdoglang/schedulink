<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class CalendarEventController extends Controller
{
    public function index()
    {
        $events = Booking::where('status', 'approved') // or any logic you want
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->purpose,
                    'start' => $booking->scheduled_date . 'T' . $booking->time_from,
                    'end' => $booking->scheduled_date . 'T' . $booking->time_to,
                    'extendedProps' => [
                        'notes' => $booking->notes,
                        'destination' => $booking->destination,
                        'asset_name' => $booking->asset_name,
                    ],
                ];
            });

        return response()->json($events);
    }
}


