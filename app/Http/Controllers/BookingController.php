<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AssetType;
use App\Models\AssetDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $bookings = Booking::with([
            'assetType',
            'assetDetail',
            'user',
            'bookedGuests',
            'approvalLogs.approver.user'
        ])->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    public function create(): JsonResponse
    {
        $assetTypes = AssetType::all();
        $assetDetails = AssetDetail::with('assetType')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'asset_types' => $assetTypes,
                'asset_details' => $assetDetails,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'asset_type_id' => 'required|exists:asset_types,id',
                'asset_detail_id' => 'required|exists:asset_details,id',
                'user_id' => 'required|exists:users,id',
                'purpose' => 'required|string',
                'no_of_seats' => 'required|integer|min:1',
                'destination' => 'required|string',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'time_from' => 'required|date_format:H:i',
                'time_to' => 'required|date_format:H:i|after:time_from',
                'notes' => 'nullable|string',
                'guest_emails' => 'nullable|array',
                'guest_emails.*' => 'email',
            ]);

            // Check if asset is available for the requested time
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

            $booking = Booking::create([
                'asset_type_id' => $validated['asset_type_id'],
                'asset_detail_id' => $validated['asset_detail_id'],
                'user_id' => $validated['user_id'],
                'purpose' => $validated['purpose'],
                'no_of_seats' => $validated['no_of_seats'],
                'destination' => $validated['destination'],
                'scheduled_date' => $validated['scheduled_date'],
                'time_from' => $validated['time_from'],
                'time_to' => $validated['time_to'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Add guest emails if provided
            if (!empty($validated['guest_emails'])) {
                foreach ($validated['guest_emails'] as $email) {
                    $booking->bookedGuests()->create(['email' => $email]);
                }
            }

            DB::commit();

            $booking->load(['assetType', 'assetDetail', 'user', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking,
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking',
            ], 500);
        }
    }

    public function show(Booking $booking): JsonResponse
    {
        $booking->load([
            'assetType',
            'assetDetail',
            'user',
            'bookedGuests',
            'vehicleDriverAssignments.driver',
            'approvalLogs.approver.user'
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    public function edit(Booking $booking): JsonResponse
    {
        $assetTypes = AssetType::all();
        $assetDetails = AssetDetail::with('assetType')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'booking' => $booking->load(['bookedGuests']),
                'asset_types' => $assetTypes,
                'asset_details' => $assetDetails,
            ],
        ]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        // Only allow updates for pending bookings
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending bookings can be updated',
            ], 422);
        }

        try {
            $validated = $request->validate([
                'asset_type_id' => 'required|exists:asset_types,id',
                'asset_detail_id' => 'required|exists:asset_details,id',
                'purpose' => 'required|string',
                'no_of_seats' => 'required|integer|min:1',
                'destination' => 'required|string',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'time_from' => 'required|date_format:H:i',
                'time_to' => 'required|date_format:H:i|after:time_from',
                'notes' => 'nullable|string',
                'guest_emails' => 'nullable|array',
                'guest_emails.*' => 'email',
            ]);

            // Check availability (excluding current booking)
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

            $booking->update([
                'asset_type_id' => $validated['asset_type_id'],
                'asset_detail_id' => $validated['asset_detail_id'],
                'purpose' => $validated['purpose'],
                'no_of_seats' => $validated['no_of_seats'],
                'destination' => $validated['destination'],
                'scheduled_date' => $validated['scheduled_date'],
                'time_from' => $validated['time_from'],
                'time_to' => $validated['time_to'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update guest emails
            $booking->bookedGuests()->delete();
            if (!empty($validated['guest_emails'])) {
                foreach ($validated['guest_emails'] as $email) {
                    $booking->bookedGuests()->create(['email' => $email]);
                }
            }

            DB::commit();

            $booking->load(['assetType', 'assetDetail', 'user', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking,
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the booking',
            ], 500);
        }
    }

    public function destroy(Booking $booking): JsonResponse
    {
        // Only allow deletion of pending bookings
        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending bookings can be deleted',
            ], 422);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully',
        ]);
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking): JsonResponse
    {
        if (in_array($booking->status, ['cancelled', 'declined'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already cancelled or declined',
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking,
        ]);
    }

    /**
     * Get available assets for a specific date and time
     */
    public function getAvailableAssets(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'scheduled_date' => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'booking_id' => 'nullable|exists:bookings,id', // For updates
        ]);

        $unavailableAssetIds = Booking::where('asset_type_id', $validated['asset_type_id'])
            ->where('scheduled_date', $validated['scheduled_date'])
            ->where('status', '!=', 'cancelled')
            ->when($validated['booking_id'] ?? null, function ($query, $bookingId) {
                return $query->where('id', '!=', $bookingId);
            })
            ->where(function ($query) use ($validated) {
                $query->whereBetween('time_from', [$validated['time_from'], $validated['time_to']])
                      ->orWhereBetween('time_to', [$validated['time_from'], $validated['time_to']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('time_from', '<=', $validated['time_from'])
                            ->where('time_to', '>=', $validated['time_to']);
                      });
            })
            ->pluck('asset_detail_id');

        $availableAssets = AssetDetail::where('asset_type_id', $validated['asset_type_id'])
            ->whereNotIn('id', $unavailableAssetIds)
            ->with('assetType')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableAssets,
        ]);
    }
}
