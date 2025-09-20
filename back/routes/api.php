<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\SpaceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RatingReviewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Waqitly API endpoints
Route::prefix('v1')->group(function () {
    // Organization routes
    Route::apiResource('organizations', OrganizationController::class);
    
    // Space routes
    Route::apiResource('spaces', SpaceController::class);
    Route::get('organizations/{id}/spaces', [SpaceController::class, 'byOrganization']);
    
    // Category routes
    Route::apiResource('categories', CategoryController::class);
    
    // Service routes
    Route::apiResource('services', ServiceController::class);
    
    // Booking routes
    Route::apiResource('bookings', BookingController::class);
    
    // Reservation routes
    Route::apiResource('reservations', ReservationController::class);
    Route::patch('reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    
    // Rating & Review routes
    Route::apiResource('reviews', RatingReviewController::class);
    Route::get('spaces/{id}/stats', [RatingReviewController::class, 'getSpaceStats']);
});
