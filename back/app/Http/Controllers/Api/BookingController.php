<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Booking::with(['user', 'space.organization', 'items.spaceService.service']);
            
            // Filter by user if provided
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            // Filter by space if provided
            if ($request->has('space_id')) {
                $query->where('space_id', $request->space_id);
            }
            
            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            
            $bookings = $query->orderBy('date', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $bookings,
                'message' => 'Bookings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created booking.
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
                'services' => 'nullable|array',
                'services.*.space_service_id' => 'required_with:services|exists:space_services,id',
                'services.*.quantity' => 'required_with:services|integer|min:1',
                'services.*.price' => 'required_with:services|integer|min:0'
            ]);

            // Check space availability
            $conflictingBookings = Booking::where('space_id', $validated['space_id'])
                ->where('date', $validated['date'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($subQuery) use ($validated) {
                            $subQuery->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflictingBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space is not available at the requested time'
                ], 422);
            }

            $booking = Booking::create([
                'user_id' => $validated['user_id'],
                'space_id' => $validated['space_id'],
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'total_price' => $validated['total_price']
            ]);

            // Add booking services if provided
            if (isset($validated['services'])) {
                foreach ($validated['services'] as $service) {
                    $booking->items()->create([
                        'space_service_id' => $service['space_service_id'],
                        'quantity' => $service['quantity'],
                        'price' => $service['price']
                    ]);
                }
            }

            $booking->load(['user', 'space.organization', 'items.spaceService.service']);

            return response()->json([
                'success' => true,
                'data' => $booking,
                'message' => 'Booking created successfully'
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
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $booking = Booking::with(['user', 'space.organization', 'space.location', 'items.spaceService.service'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $booking,
                'message' => 'Booking retrieved successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Check if booking can be updated (only future bookings)
            if (Carbon::parse($booking->date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update past bookings'
                ], 422);
            }
            
            $validated = $request->validate([
                'date' => 'sometimes|required|date|after_or_equal:today',
                'start_time' => 'sometimes|required|date_format:H:i:s',
                'end_time' => 'sometimes|required|date_format:H:i:s|after:start_time',
                'total_price' => 'sometimes|required|integer|min:0'
            ]);

            // Check availability if time/date is being changed
            if (isset($validated['date']) || isset($validated['start_time']) || isset($validated['end_time'])) {
                $checkDate = $validated['date'] ?? $booking->date;
                $checkStartTime = $validated['start_time'] ?? $booking->start_time;
                $checkEndTime = $validated['end_time'] ?? $booking->end_time;

                $conflictingBookings = Booking::where('space_id', $booking->space_id)
                    ->where('id', '!=', $booking->id)
                    ->where('date', $checkDate)
                    ->where(function ($query) use ($checkStartTime, $checkEndTime) {
                        $query->whereBetween('start_time', [$checkStartTime, $checkEndTime])
                            ->orWhereBetween('end_time', [$checkStartTime, $checkEndTime])
                            ->orWhere(function ($subQuery) use ($checkStartTime, $checkEndTime) {
                                $subQuery->where('start_time', '<=', $checkStartTime)
                                    ->where('end_time', '>=', $checkEndTime);
                            });
                    })
                    ->exists();

                if ($conflictingBookings) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Space is not available at the requested time'
                    ], 422);
                }
            }

            $booking->update($validated);

            return response()->json([
                'success' => true,
                'data' => $booking->fresh(['user', 'space.organization', 'items.spaceService.service']),
                'message' => 'Booking updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
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
                'message' => 'Failed to update booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Check if booking can be cancelled (only future bookings)
            if (Carbon::parse($booking->date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel past bookings'
                ], 422);
            }

            // Delete associated booking services first
            $booking->items()->delete();
            
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
