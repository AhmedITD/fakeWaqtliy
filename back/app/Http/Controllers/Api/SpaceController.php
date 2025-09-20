<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Space;
use App\Models\SpaceLocation;
use App\Models\SpaceImage;
use App\Models\SpaceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class SpaceController extends Controller
{
    /**
     * Display a listing of spaces with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Space::with([
            'category:id,name,image',
            'organization:id,name,description,image',
            'locations',
            'images',
            'services' => function ($q) {
                $q->with('service:id,name');
            }
        ]);

        // Filter by search term
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filter by organization
        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->get('organization_id'));
        }

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->get('min_capacity'));
        }

        if ($request->has('max_capacity')) {
            $query->where('capacity', '<=', $request->get('max_capacity'));
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price_per_hour', '>=', $request->get('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price_per_hour', '<=', $request->get('max_price'));
        }

        // Filter by location (within radius)
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->get('latitude');
            $lng = $request->get('longitude');
            $radius = $request->get('radius'); // in kilometers
            
            $query->whereHas('locations', function ($q) use ($lat, $lng, $radius) {
                $q->selectRaw("*, (
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) AS distance", [$lat, $lng, $lat])
                ->having('distance', '<=', $radius);
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'price_per_hour', 'capacity', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $spaces = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $spaces,
            'message' => 'Spaces retrieved successfully'
        ]);
    }

    /**
     * Store a newly created space in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'size' => 'nullable|integer|min:1',
            'capacity' => 'nullable|integer|min:1',
            'floor' => 'nullable|string|max:255',
            'price_per_hour' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'locations' => 'array',
            'locations.*.latitude' => 'nullable|numeric|between:-90,90',
            'locations.*.longitude' => 'nullable|numeric|between:-180,180',
            'locations.*.location_written' => 'nullable|string|max:255',
            'images' => 'array',
            'images.*.image' => 'required|string|max:255',
            'images.*.low_res_image' => 'nullable|string|max:255',
            'services' => 'array',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.price' => 'required|integer|min:0',
        ]);

        $locations = $validated['locations'] ?? [];
        $images = $validated['images'] ?? [];
        $services = $validated['services'] ?? [];
        unset($validated['locations'], $validated['images'], $validated['services']);

        $space = Space::create($validated);

        // Create locations
        foreach ($locations as $locationData) {
            $locationData['space_id'] = $space->id;
            SpaceLocation::create($locationData);
        }

        // Create images
        foreach ($images as $imageData) {
            $imageData['space_id'] = $space->id;
            SpaceImage::create($imageData);
        }

        // Create services
        foreach ($services as $serviceData) {
            $serviceData['space_id'] = $space->id;
            SpaceService::create($serviceData);
        }

        $space->load([
            'category:id,name',
            'organization:id,name',
            'locations',
            'images',
            'services.service:id,name'
        ]);

        return response()->json([
            'success' => true,
            'data' => $space,
            'message' => 'Space created successfully'
        ], 201);
    }

    /**
     * Display the specified space.
     */
    public function show(string $id): JsonResponse
    {
        $space = Space::with([
            'category:id,name,image',
            'organization' => function ($query) {
                $query->select('id', 'name', 'description', 'email', 'image')
                      ->with('owner:id,full_name,email,image');
            },
            'locations',
            'images',
            'services' => function ($q) {
                $q->with('service:id,name');
            },
            'ratingReviews' => function ($q) {
                $q->with('user:id,full_name,image')
                  ->orderBy('created_at', 'desc')
                  ->limit(10);
            }
        ])->find($id);

        if (!$space) {
            return response()->json([
                'success' => false,
                'message' => 'Space not found'
            ], 404);
        }

        // Calculate average rating
        $avgRating = $space->ratingReviews()->avg('rating');
        $space->average_rating = round($avgRating, 1);
        $space->total_reviews = $space->ratingReviews()->count();

        return response()->json([
            'success' => true,
            'data' => $space,
            'message' => 'Space retrieved successfully'
        ]);
    }

    /**
     * Update the specified space in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $space = Space::find($id);

        if (!$space) {
            return response()->json([
                'success' => false,
                'message' => 'Space not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'size' => 'nullable|integer|min:1',
            'capacity' => 'nullable|integer|min:1',
            'floor' => 'nullable|string|max:255',
            'price_per_hour' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        $space->update($validated);
        $space->load([
            'category:id,name',
            'organization:id,name',
            'locations',
            'images',
            'services.service:id,name'
        ]);

        return response()->json([
            'success' => true,
            'data' => $space,
            'message' => 'Space updated successfully'
        ]);
    }

    /**
     * Remove the specified space from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $space = Space::find($id);

        if (!$space) {
            return response()->json([
                'success' => false,
                'message' => 'Space not found'
            ], 404);
        }

        $space->delete();

        return response()->json([
            'success' => true,
            'message' => 'Space deleted successfully'
        ]);
    }

    /**
     * Get spaces by organization.
     */
    public function byOrganization(string $organizationId): JsonResponse
    {
        $spaces = Space::with([
            'category:id,name',
            'locations',
            'images',
            'services.service:id,name'
        ])
        ->where('organization_id', $organizationId)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $spaces,
            'message' => 'Organization spaces retrieved successfully'
        ]);
    }
}
