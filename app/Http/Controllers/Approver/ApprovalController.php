<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Approver;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovalController extends Controller
{
    /**
     * Get pending bookings for approval
     */
    public function getPendingApprovals(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $query = Booking::with(['user', 'assetType', 'assetDetail', 'bookedGuests'])
                ->where('status', 'pending')
                ->whereHas('assetType.approvers', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });

            // Filter by asset type if provided
            if ($request->has('asset_type_id') && $request->asset_type_id != '') {
                $query->where('asset_type_id', $request->asset_type_id);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from != '') {
                $query->where('scheduled_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->where('scheduled_date', '<=', $request->date_to);
            }

            $bookings = $query->orderBy('created_at', 'asc')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching pending approvals'
            ], 500);
        }
    }

    /**
     * Approve a booking
     */
    public function approve(Request $request, string $bookingId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'comments' => 'nullable|string|max:500',
            ]);

            $userId = Auth::id();

            // Check if user is authorized to approve this booking
            $booking = Booking::with(['assetType'])
                ->where('id', $bookingId)
                ->where('status', 'pending')
                ->whereHas('assetType.approvers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or you are not authorized to approve it'
                ], 404);
            }

            // Get the approver record
            $approver = Approver::where('asset_type_id', $booking->asset_type_id)
                ->where('user_id', $userId)
                ->first();

            if (!$approver) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to approve this type of booking'
                ], 403);
            }

            DB::beginTransaction();

            // Check if this is the final approval level
            $maxApprovalLevel = Approver::where('asset_type_id', $booking->asset_type_id)
                ->max('approval_level');

            $isLastApprover = $approver->approval_level == $maxApprovalLevel;

            // Create approval log
            ApprovalLog::create([
                'booking_id' => $booking->id,
                'approver_id' => $approver->id,
                'approver_user_id' => $userId,
                'status' => 'approved',
                'comments' => $validated['comments'],
                'approval_level' => $approver->approval_level,
            ]);

            // Update booking status if this is the final approval
            if ($isLastApprover) {
                $booking->update(['status' => 'approved']);
            }

            DB::commit();

            // Load relationships for response
            $booking->load(['user', 'assetType', 'assetDetail', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => $isLastApprover ? 'Booking approved successfully' : 'Approval recorded, waiting for higher level approval',
                'data' => [
                    'booking' => $booking,
                    'is_final_approval' => $isLastApprover,
                    'approval_level' => $approver->approval_level,
                    'max_approval_level' => $maxApprovalLevel,
                ]
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
                'message' => 'An error occurred while approving the booking'
            ], 500);
        }
    }

    /**
     * Reject a booking
     */
    public function reject(Request $request, string $bookingId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'comments' => 'required|string|max:500',
            ]);

            $userId = Auth::id();

            // Check if user is authorized to reject this booking
            $booking = Booking::with(['assetType'])
                ->where('id', $bookingId)
                ->where('status', 'pending')
                ->whereHas('assetType.approvers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or you are not authorized to reject it'
                ], 404);
            }

            // Get the approver record
            $approver = Approver::where('asset_type_id', $booking->asset_type_id)
                ->where('user_id', $userId)
                ->first();

            if (!$approver) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject this type of booking'
                ], 403);
            }

            DB::beginTransaction();

            // Create approval log
            ApprovalLog::create([
                'booking_id' => $booking->id,
                'approver_id' => $approver->id,
                'approver_user_id' => $userId,
                'status' => 'rejected',
                'comments' => $validated['comments'],
                'approval_level' => $approver->approval_level,
            ]);

            // Update booking status to rejected
            $booking->update(['status' => 'rejected']);

            DB::commit();

            // Load relationships for response
            $booking->load(['user', 'assetType', 'assetDetail', 'bookedGuests']);

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected successfully',
                'data' => [
                    'booking' => $booking,
                    'approval_level' => $approver->approval_level,
                ]
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
                'message' => 'An error occurred while rejecting the booking'
            ], 500);
        }
    }

    /**
     * Get approval history for a booking
     */
    public function getApprovalHistory(string $bookingId): JsonResponse
    {
        try {
            $userId = Auth::id();

            // Verify user can view this booking's approval history
            $booking = Booking::where('id', $bookingId)
                ->whereHas('assetType.approvers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or you are not authorized to view its approval history'
                ], 404);
            }

            $approvalHistory = ApprovalLog::with(['approver.user', 'approver.assetType'])
                ->where('booking_id', $bookingId)
                ->orderBy('approval_level', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'approval_history' => $approvalHistory,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching approval history'
            ], 500);
        }
    }

    /**
     * Get approval workflow for an asset type
     */
    public function getApprovalWorkflow(string $assetTypeId): JsonResponse
    {
        try {
            $userId = Auth::id();

            // Check if user is an approver for this asset type
            $isApprover = Approver::where('asset_type_id', $assetTypeId)
                ->where('user_id', $userId)
                ->exists();

            if (!$isApprover) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this approval workflow'
                ], 403);
            }

            $workflow = Approver::with(['user', 'assetType'])
                ->where('asset_type_id', $assetTypeId)
                ->orderBy('approval_level', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $workflow
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching approval workflow'
            ], 500);
        }
    }

    /**
     * Bulk approve multiple bookings
     */
    public function bulkApprove(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'booking_ids' => 'required|array|min:1',
                'booking_ids.*' => 'exists:bookings,id',
                'comments' => 'nullable|string|max:500',
            ]);

            $userId = Auth::id();
            $approvedCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($validated['booking_ids'] as $bookingId) {
                try {
                    // Check authorization for each booking
                    $booking = Booking::with(['assetType'])
                        ->where('id', $bookingId)
                        ->where('status', 'pending')
                        ->whereHas('assetType.approvers', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        })
                        ->first();

                    if (!$booking) {
                        $errors[] = "Booking ID {$bookingId}: Not found or not authorized";
                        continue;
                    }

                    $approver = Approver::where('asset_type_id', $booking->asset_type_id)
                        ->where('user_id', $userId)
                        ->first();

                    if (!$approver) {
                        $errors[] = "Booking ID {$bookingId}: Not authorized for this asset type";
                        continue;
                    }

                    // Create approval log
                    ApprovalLog::create([
                        'booking_id' => $booking->id,
                        'approver_id' => $approver->id,
                        'approver_user_id' => $userId,
                        'status' => 'approved',
                        'comments' => $validated['comments'],
                        'approval_level' => $approver->approval_level,
                    ]);

                    // Check if this is the final approval level
                    $maxApprovalLevel = Approver::where('asset_type_id', $booking->asset_type_id)
                        ->max('approval_level');

                    if ($approver->approval_level == $maxApprovalLevel) {
                        $booking->update(['status' => 'approved']);
                    }

                    $approvedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Booking ID {$bookingId}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$approvedCount} bookings processed successfully",
                'data' => [
                    'approved_count' => $approvedCount,
                    'errors' => $errors,
                ]
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
                'message' => 'An error occurred while processing bulk approvals'
            ], 500);
        }
    }

    /**
     * Get my approval history
     */
    public function getMyApprovalHistory(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $query = ApprovalLog::with(['booking.user', 'booking.assetType', 'booking.assetDetail'])
                ->where('approver_user_id', $userId);

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $approvalHistory = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $approvalHistory
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching approval history'
            ], 500);
        }
    }
}