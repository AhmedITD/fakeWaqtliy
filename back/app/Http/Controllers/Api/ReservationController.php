<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\ReservationStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Reservation::with(['user', 'space.organization', 'details']);
            
            // Filter by user if provided
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            // Filter by space if provided
            if ($request->has('space_id')) {
                $query->where('space_id', $request->space_id);
            }
            
            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            
            $reservations = $query->orderBy('date', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $reservations,
                'message' => 'Reservations retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reservations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created reservation.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'space_id' => 'required|exists:spaces,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i:s',
                'end_time' => 'required|date_format:H:i:s|after:start_time',
                'total_price' => 'required|integer|min:0',
                'details' => 'nullable|array',
                'details.*.description' => 'required_with:details|string|max:500',
                'details.*.value' => 'required_with:details|string|max:255'
            ]);

            // Check space availability
            $conflictingReservations = Reservation::where('space_id', $validated['space_id'])
                ->where('date', $validated['date'])
                ->where('status', '!=', ReservationStatus::CANCELLED)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($subQuery) use ($validated) {
                            $subQuery->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflictingReservations) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space is not available at the requested time'
                ], 422);
            }

            $reservation = Reservation::create([
                'user_id' => $validated['user_id'],
                'space_id' => $validated['space_id'],
                'status' => ReservationStatus::PENDING,
                'total_price' => $validated['total_price'],
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time']
            ]);

            // Add reservation details if provided
            if (isset($validated['details'])) {
                foreach ($validated['details'] as $detail) {
                    $reservation->details()->create([
                        'description' => $detail['description'],
                        'value' => $detail['value']
                    ]);
                }
            }

            $reservation->load(['user', 'space.organization', 'details']);

            return response()->json([
                'success' => true,
                'data' => $reservation,
                'message' => 'Reservation created successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified reservation.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $reservation = Reservation::with(['user', 'space.organization', 'space.location', 'details'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $reservation,
                'message' => 'Reservation retrieved successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified reservation.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'sometimes|required|in:' . implode(',', [
                    ReservationStatus::PENDING->value,
                    ReservationStatus::CONFIRMED->value,
                    ReservationStatus::CANCELLED->value,
                    ReservationStatus::COMPLETED->value
                ]),
                'date' => 'sometimes|required|date|after_or_equal:today',
                'start_time' => 'sometimes|required|date_format:H:i:s',
                'end_time' => 'sometimes|required|date_format:H:i:s|after:start_time',
                'total_price' => 'sometimes|required|integer|min:0'
            ]);

            // Check availability if time/date is being changed and not cancelled
            if ((isset($validated['date']) || isset($validated['start_time']) || isset($validated['end_time'])) 
                && (!isset($validated['status']) || $validated['status'] !== ReservationStatus::CANCELLED->value)) {
                
                $checkDate = $validated['date'] ?? $reservation->date;
                $checkStartTime = $validated['start_time'] ?? $reservation->start_time;
                $checkEndTime = $validated['end_time'] ?? $reservation->end_time;

                $conflictingReservations = Reservation::where('space_id', $reservation->space_id)
                    ->where('id', '!=', $reservation->id)
                    ->where('date', $checkDate)
                    ->where('status', '!=', ReservationStatus::CANCELLED)
                    ->where(function ($query) use ($checkStartTime, $checkEndTime) {
                        $query->whereBetween('start_time', [$checkStartTime, $checkEndTime])
                            ->orWhereBetween('end_time', [$checkStartTime, $checkEndTime])
                            ->orWhere(function ($subQuery) use ($checkStartTime, $checkEndTime) {
                                $subQuery->where('start_time', '<=', $checkStartTime)
                                    ->where('end_time', '>=', $checkEndTime);
                            });
                    })
                    ->exists();

                if ($conflictingReservations) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Space is not available at the requested time'
                    ], 422);
                }
            }

            // Set cancelled_at timestamp if status is being changed to cancelled
            if (isset($validated['status']) && $validated['status'] === ReservationStatus::CANCELLED->value) {
                $validated['cancelled_at'] = now();
            }

            $reservation->update($validated);

            return response()->json([
                'success' => true,
                'data' => $reservation->fresh(['user', 'space.organization', 'details']),
                'message' => 'Reservation updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified reservation.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Delete associated reservation details first
            $reservation->details()->delete();
            
            $reservation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reservation deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel the specified reservation.
     */
    public function cancel(string $id): JsonResponse
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            if ($reservation->status === ReservationStatus::CANCELLED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation is already cancelled'
                ], 422);
            }

            if ($reservation->status === ReservationStatus::COMPLETED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel completed reservation'
                ], 422);
            }

            $reservation->update([
                'status' => ReservationStatus::CANCELLED,
                'cancelled_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'data' => $reservation->fresh(['user', 'space.organization', 'details']),
                'message' => 'Reservation cancelled successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
