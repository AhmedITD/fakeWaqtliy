<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

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

// Map-related API endpoints
Route::prefix('map')->controller(MapController::class)->group(function () {
    Route::get('/markers', 'getMarkers');
    Route::post('/markers', 'addMarker');
    Route::get('/config', 'getConfig');
    Route::get('/search', 'searchLocations');
});
