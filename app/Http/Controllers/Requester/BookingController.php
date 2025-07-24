<?php

namespace App\Http\Controllers\Requester;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\AssetType;
use App\Models\AssetDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Display user's bookings
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $query = Booking::with(['assetType', 'assetDetail', 'bookedGuests'])
                ->where('user_id', $userId);

            // Filter by status if provided
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by date range if provided
            if ($request->has('date_from') && $request->date_from != '') {
                $query->where('scheduled_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->where('scheduled_date', '<=', $request->date_to);
            }

            $bookings = $query->orderBy('scheduled_date', 'desc')
                ->orderBy('time_from', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching bookings'
            ], 500);
        }
    }

    /**
     * Store a new booking
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'asset_type_id' => 'required|exists:asset_types,id',
                'asset_detail_id' => 'required|exists:asset_details,id',
                'purpose' => 'required|string|max:255',
                'no_of_seats' => 'required|integer|min:1',
                'destination' => 'required|string|max:255',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'time_from' => 'required|date_format:H:i',
                'time_to' => 'required|date_format:H:i|after:time_from',
                'notes' => 'nullable|string',
                'guest_emails' => 'nullable|array',
                'guest_emails.*' => 'email',
            ]);

            // Check asset availability
            $conflictingBooking = Booking::where('asset_detail_id', $validated['asset_detail_id'])
                ->where('scheduled_date', $validated['scheduled_date'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('time_from', [$validated['time_from'], $validated['time_to']])
                        ->orWhereBetween('time_to', [$validated['time_from'], $validated['time_to']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('time_from', '<=', $validated['time_from'])
                                ->where('time_to', '>=', $validated['time_to']);
                        });
                })
                ->exists();

            if ($conflictingBooking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset is not available for the selected time slot',
                ], 422);
            }

            DB::beginTransaction();

            // Create booking
            $booking = Booking::create([
                'asset_type_id' => $validated['asset_type_id'],
                'asset_detail_id' => $validated['asset_detail_id'],
                'user_id' => Auth::id(),
                'purpose' => $validated['purpose'],
                'no_of_seats' => $validated['no_of_seats'],
                'destination' => $validated['destination'],
                'scheduled_date' => $validated['scheduled_date'],
                'time_from' => $validated['time_from'],
                'time_to' => $validated['time_to'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            // Add guest emails if provided
            if (!empty($validated['guest_emails'])) {
                foreach ($validated['guest_emails'] as $email) {
                    $booking->bookedGuests()->create(['email' => $email]);
                }
            }

            DB::commit();

            // Load relationships for response
            $booking->load(['assetType', 'assetDetail', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking'
            ], 500);
        }
    }

    /**
     * Show a specific booking
     */
    public function show(string $id): JsonResponse
    {
        try {
            $booking = Booking::with(['assetType', 'assetDetail', 'user', 'bookedGuests'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Update a booking (only if pending)
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $booking = Booking::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->findOrFail($id);

            $validated = $request->validate([
                'asset_type_id' => 'required|exists:asset_types,id',
                'asset_detail_id' => 'required|exists:asset_details,id',
                'purpose' => 'required|string|max:255',
                'no_of_seats' => 'required|integer|min:1',
                'destination' => 'required|string|max:255',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'time_from' => 'required|date_format:H:i',
                'time_to' => 'required|date_format:H:i|after:time_from',
                'notes' => 'nullable|string',
                'guest_emails' => 'nullable|array',
                'guest_emails.*' => 'email',
            ]);

            // Check asset availability (excluding current booking)
            $conflictingBooking = Booking::where('asset_detail_id', $validated['asset_detail_id'])
                ->where('scheduled_date', $validated['scheduled_date'])
                ->where('status', '!=', 'cancelled')
                ->where('id', '!=', $booking->id)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('time_from', [$validated['time_from'], $validated['time_to']])
                        ->orWhereBetween('time_to', [$validated['time_from'], $validated['time_to']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('time_from', '<=', $validated['time_from'])
                                ->where('time_to', '>=', $validated['time_to']);
                        });
                })
                ->exists();

            if ($conflictingBooking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset is not available for the selected time slot',
                ], 422);
            }

            DB::beginTransaction();

            // Update booking
            $booking->update([
                'asset_type_id' => $validated['asset_type_id'],
                'asset_detail_id' => $validated['asset_detail_id'],
                'purpose' => $validated['purpose'],
                'no_of_seats' => $validated['no_of_seats'],
                'destination' => $validated['destination'],
                'scheduled_date' => $validated['scheduled_date'],
                'time_from' => $validated['time_from'],
                'time_to' => $validated['time_to'],
                'notes' => $validated['notes'],
            ]);

            // Update guest emails
            $booking->bookedGuests()->delete();
            if (!empty($validated['guest_emails'])) {
                foreach ($validated['guest_emails'] as $email) {
                    $booking->bookedGuests()->create(['email' => $email]);
                }
            }

            DB::commit();

            // Load relationships for response
            $booking->load(['assetType', 'assetDetail', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or cannot be updated'
            ], 404);
        }
    }

    /**
     * Cancel a booking (only if pending or approved)
     */
    public function cancel(string $id): JsonResponse
    {
        try {
            $booking = Booking::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'approved'])
                ->findOrFail($id);

            $booking->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or cannot be cancelled'
            ], 404);
        }
    }

    /**
     * Get available assets for booking
     */
    public function getAvailableAssets(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'asset_type_id' => 'required|exists:asset_types,id',
                'scheduled_date' => 'required|date',
                'time_from' => 'required|date_format:H:i',
                'time_to' => 'required|date_format:H:i|after:time_from',
            ]);

            $bookedAssetIds = Booking::where('scheduled_date', $request->scheduled_date)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('time_from', [$request->time_from, $request->time_to])
                        ->orWhereBetween('time_to', [$request->time_from, $request->time_to])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('time_from', '<=', $request->time_from)
                                ->where('time_to', '>=', $request->time_to);
                        });
                })
                ->pluck('asset_detail_id');

            $availableAssets = AssetDetail::where('asset_type_id', $request->asset_type_id)
                ->whereNotIn('id', $bookedAssetIds)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $availableAssets
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching available assets'
            ], 500);
        }
    }
}