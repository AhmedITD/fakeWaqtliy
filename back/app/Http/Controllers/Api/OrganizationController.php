<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations with their locations and spaces.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Organization::with([
            'owner:id,full_name,email,image',
            'locations',
            'spaces' => function ($query) {
                $query->with([
                    'category:id,name',
                    'locations',
                    'images',
                    'services.service:id,name'
                ]);
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

        // Filter by owner
        // if ($request->has('owner_id')) {
        //     $query->where('owner_id', $request->get('owner_id'));
        // }

        $organizations = $query->get();

        return response()->json([
            'success' => true,
            'data' => $organizations,
            'message' => 'Organizations retrieved successfully'
        ]);
    }
    /**
     * Display the specified organization.
     */
    public function show(string $id): JsonResponse
    {
        $organization = Organization::with([
            'owner:id,full_name,email,image',
            'locations',
            'spaces' => function ($query) {
                $query->with([
                    'category:id,name',
                    'locations',
                    'images',
                    'services' => function ($q) {
                        $q->with('service:id,name');
                    }
                ]);
            }
        ])->find($id);

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Organization not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $organization,
            'message' => 'Organization retrieved successfully'
        ]);
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'owner_id' => 'required|exists:users,id',
                'description' => 'nullable|string|max:1000',
                'email' => 'nullable|email|max:255',
                'image' => 'nullable|string|max:500',
                'locations' => 'nullable|array',
                'locations.*.latitude' => 'required_with:locations|numeric|between:-90,90',
                'locations.*.longitude' => 'required_with:locations|numeric|between:-180,180',
                'locations.*.address' => 'required_with:locations|string|max:500'
            ]);

            $organization = Organization::create([
                'name' => $validated['name'],
                'owner_id' => $validated['owner_id'],
                'description' => $validated['description'] ?? null,
                'email' => $validated['email'] ?? null,
                'image' => $validated['image'] ?? null
            ]);

            // Add locations if provided
            if (isset($validated['locations'])) {
                foreach ($validated['locations'] as $location) {
                    $organization->locations()->create([
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'address' => $location['address']
                    ]);
                }
            }

            $organization->load(['owner:id,full_name,email,image', 'locations']);

            return response()->json([
                'success' => true,
                'data' => $organization,
                'message' => 'Organization created successfully'
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
                'message' => 'Failed to create organization',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified organization.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $organization = Organization::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'owner_id' => 'sometimes|required|exists:users,id',
                'description' => 'nullable|string|max:1000',
                'email' => 'nullable|email|max:255',
                'image' => 'nullable|string|max:500',
                'locations' => 'nullable|array',
                'locations.*.id' => 'nullable|exists:organization_locations,id',
                'locations.*.latitude' => 'required_with:locations|numeric|between:-90,90',
                'locations.*.longitude' => 'required_with:locations|numeric|between:-180,180',
                'locations.*.address' => 'required_with:locations|string|max:500'
            ]);

            $organization->update([
                'name' => $validated['name'] ?? $organization->name,
                'owner_id' => $validated['owner_id'] ?? $organization->owner_id,
                'description' => $validated['description'] ?? $organization->description,
                'email' => $validated['email'] ?? $organization->email,
                'image' => $validated['image'] ?? $organization->image
            ]);

            // Update locations if provided
            if (isset($validated['locations'])) {
                // Delete existing locations that are not in the update
                $existingLocationIds = collect($validated['locations'])
                    ->whereNotNull('id')
                    ->pluck('id')
                    ->toArray();
                
                $organization->locations()
                    ->whereNotIn('id', $existingLocationIds)
                    ->delete();

                // Update or create locations
                foreach ($validated['locations'] as $locationData) {
                    if (isset($locationData['id'])) {
                        // Update existing location
                        $organization->locations()
                            ->where('id', $locationData['id'])
                            ->update([
                                'latitude' => $locationData['latitude'],
                                'longitude' => $locationData['longitude'],
                                'address' => $locationData['address']
                            ]);
                    } else {
                        // Create new location
                        $organization->locations()->create([
                            'latitude' => $locationData['latitude'],
                            'longitude' => $locationData['longitude'],
                            'address' => $locationData['address']
                        ]);
                    }
                }
            }

            $organization->load(['owner:id,full_name,email,image', 'locations', 'spaces']);

            return response()->json([
                'success' => true,
                'data' => $organization,
                'message' => 'Organization updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Organization not found'
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
                'message' => 'Failed to update organization',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified organization.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $organization = Organization::findOrFail($id);
            
            // Check if organization has spaces
            if ($organization->spaces()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete organization with associated spaces'
                ], 422);
            }

            // Delete associated locations
            $organization->locations()->delete();
            
            $organization->delete();

            return response()->json([
                'success' => true,
                'message' => 'Organization deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Organization not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete organization',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}