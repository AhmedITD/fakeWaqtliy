<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RatingReview;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RatingReviewController extends Controller
{
    /**
     * Display a listing of rating reviews.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = RatingReview::with(['user', 'space.organization']);
            
            // Filter by user if provided
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            // Filter by space if provided
            if ($request->has('space_id')) {
                $query->where('space_id', $request->space_id);
            }
            
            // Filter by minimum rating if provided
            if ($request->has('min_rating')) {
                $query->where('rating', '>=', $request->min_rating);
            }
            
            $reviews = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $reviews,
                'message' => 'Reviews retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created rating review.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
                'user_id' => 'required|exists:users,id',
                'space_id' => 'required|exists:spaces,id'
            ]);

            // Check if user has already reviewed this space
            $existingReview = RatingReview::where('user_id', $validated['user_id'])
                ->where('space_id', $validated['space_id'])
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this space. Use update to modify your review.'
                ], 422);
            }

            $ratingReview = RatingReview::create($validated);
            $ratingReview->load(['user', 'space.organization']);

            return response()->json([
                'success' => true,
                'data' => $ratingReview,
                'message' => 'Review created successfully'
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
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified rating review.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $ratingReview = RatingReview::with(['user', 'space.organization', 'space.images'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $ratingReview,
                'message' => 'Review retrieved successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified rating review.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $ratingReview = RatingReview::findOrFail($id);
            
            $validated = $request->validate([
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000'
            ]);

            $ratingReview->update($validated);

            return response()->json([
                'success' => true,
                'data' => $ratingReview->fresh(['user', 'space.organization']),
                'message' => 'Review updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
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
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified rating review.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $ratingReview = RatingReview::findOrFail($id);
            $ratingReview->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews statistics for a space.
     */
    public function getSpaceStats(string $spaceId): JsonResponse
    {
        try {
            $space = Space::findOrFail($spaceId);
            
            $reviews = RatingReview::where('space_id', $spaceId)->get();
            
            $stats = [
                'total_reviews' => $reviews->count(),
                'average_rating' => $reviews->avg('rating') ? round($reviews->avg('rating'), 2) : 0,
                'rating_distribution' => [
                    '5_stars' => $reviews->where('rating', 5)->count(),
                    '4_stars' => $reviews->where('rating', 4)->count(),
                    '3_stars' => $reviews->where('rating', 3)->count(),
                    '2_stars' => $reviews->where('rating', 2)->count(),
                    '1_star' => $reviews->where('rating', 1)->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Space rating statistics retrieved successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Space not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
