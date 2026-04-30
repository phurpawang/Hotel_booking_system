<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityApiController;

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

// ===== AVAILABILITY API (PUBLIC) =====
Route::prefix('availability')->group(function () {
    Route::get('/disabled-dates', [AvailabilityApiController::class, 'getDisabledDates']);
});

// ===== ROOM AVAILABILITY API =====
Route::prefix('rooms')->group(function () {
    Route::get('/{id}/availability', [\App\Http\Controllers\RoomController::class, 'availability'])->middleware('auth:web');
});
