<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'assetType'])->latest()->get();
        $assetTypes = AssetType::all();

        return view('bookings.index', compact('bookings', 'assetTypes'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'destination' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'purpose' => 'required|string|max:1000',
        ]);

        $booking = new Booking($validated);
        $booking->user_id = Auth::id();
        $booking->status = 'pending';
        $booking->save();

        return redirect()->back()->with('success', 'Asset reservation successfully created.');
    }
}
