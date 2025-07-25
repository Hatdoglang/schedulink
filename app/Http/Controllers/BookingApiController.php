<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'assetType', 'assetDetail']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('asset_type_id')) {
            $query->where('asset_type_id', $request->asset_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => optional($booking->assetDetail)->asset_name ?? $booking->assetType->name ?? 'Booking',
                'start' => Carbon::parse($booking->scheduled_date . ' ' . $booking->time_from)->toIso8601String(),
                'end' => Carbon::parse($booking->scheduled_date . ' ' . $booking->time_to)->toIso8601String(),
                'color' => $this->getStatusColor($booking->status),
            ];
        });

        return response()->json($events);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'approved' => '#28a745',
            'pending' => '#ffc107',
            'rejected' => '#dc3545',
            default => '#007bff',
        };
    }
}
