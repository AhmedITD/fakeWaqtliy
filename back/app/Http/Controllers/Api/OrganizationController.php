<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
            'status' => 200,
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
                'status' => 404,
                'message' => 'Organization not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $organization,
            'message' => 'Organization retrieved successfully'
        ]);
    }
}