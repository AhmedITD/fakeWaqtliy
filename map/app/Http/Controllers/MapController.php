<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    /**
     * Get all map markers
     */
    public function getMarkers(): JsonResponse
    {
        // In a real application, this would fetch from database
        $markers = [
            [
                'id' => 1,
                'name' => 'New York City',
                'coordinates' => [-74.006, 40.7128],
                'description' => 'The Big Apple',
                'type' => 'city'
            ],
            [
                'id' => 2,
                'name' => 'Brooklyn Bridge',
                'coordinates' => [-73.9969, 40.7061],
                'description' => 'Historic bridge connecting Manhattan and Brooklyn',
                'type' => 'landmark'
            ],
            [
                'id' => 3,
                'name' => 'Central Park',
                'coordinates' => [-73.9654, 40.7829],
                'description' => 'Large public park in Manhattan',
                'type' => 'park'
            ],
            [
                'id' => 4,
                'name' => 'Statue of Liberty',
                'coordinates' => [-74.0445, 40.6892],
                'description' => 'Symbol of freedom and democracy',
                'type' => 'monument'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $markers
        ]);
    }

    /**
     * Add a new marker
     */
    public function addMarker(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|array|size:2',
            'coordinates.*' => 'numeric',
            'description' => 'nullable|string|max:1000',
            'type' => 'nullable|string|in:city,landmark,park,monument,custom'
        ]);

        // In a real application, this would save to database
        $marker = [
            'id' => rand(1000, 9999),
            'name' => $validated['name'],
            'coordinates' => $validated['coordinates'],
            'description' => $validated['description'] ?? '',
            'type' => $validated['type'] ?? 'custom',
            'created_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Marker added successfully',
            'data' => $marker
        ], 201);
    }

    /**
     * Get map configuration
     */
    public function getConfig(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'defaultCenter' => [-74.5, 40],
                'defaultZoom' => 9,
                'mapStyles' => [
                    'streets' => 'mapbox://styles/mapbox/streets-v12',
                    'outdoors' => 'mapbox://styles/mapbox/outdoors-v12',
                    'light' => 'mapbox://styles/mapbox/light-v11',
                    'dark' => 'mapbox://styles/mapbox/dark-v11',
                    'satellite' => 'mapbox://styles/mapbox/satellite-v9',
                    'satellite-streets' => 'mapbox://styles/mapbox/satellite-streets-v12'
                ],
                'markerTypes' => [
                    'city' => '#FF6B6B',
                    'landmark' => '#4ECDC4',
                    'park' => '#45B7D1',
                    'monument' => '#96CEB4',
                    'custom' => '#FFEAA7'
                ]
            ]
        ]);
    }

    /**
     * Search for locations (placeholder)
     */
    public function searchLocations(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        // In a real application, this would search in database or external API
        $results = [
            [
                'id' => 'search_1',
                'name' => "Result for: {$query}",
                'coordinates' => [-74.0 + (rand(-100, 100) / 1000), 40.7 + (rand(-100, 100) / 1000)],
                'description' => "Search result for '{$query}'",
                'type' => 'search_result'
            ]
        ];

        return response()->json([
            'success' => true,
            'query' => $query,
            'data' => $results
        ]);
    }
}









