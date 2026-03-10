<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewHotelRegistrationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ReceptionistDashboardController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Restructured Authentication System
|--------------------------------------------------------------------------
*/

// ========== GUEST/PUBLIC ROUTES ==========
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ========== HOTEL AUTHENTICATION ROUTES (NO AUTH REQUIRED) ==========
// Hotel Registration (Owner Only)
Route::get('/hotel/register', [NewHotelRegistrationController::class, 'showRegistrationForm'])->name('hotel.register');
Route::post('/hotel/register', [NewHotelRegistrationController::class, 'register'])->name('hotel.register.submit');
Route::get('/hotel/registration-success', [NewHotelRegistrationController::class, 'registrationSuccess'])->name('hotel.registration.success');

// Hotel Login
Route::get('/hotel/login', [AuthController::class, 'showLogin'])->name('hotel.login');
Route::post('/hotel/login', [AuthController::class, 'login'])->name('hotel.login.submit');

// Check Registration Status
Route::get('/hotel/check-status', [NewHotelRegistrationController::class, 'showCheckStatusForm'])->name('hotel.check-status');
Route::post('/hotel/check-status', [NewHotelRegistrationController::class, 'checkStatus'])->name('hotel.check-status.submit');

// Hotel Logout (Requires Auth)
Route::post('/hotel/logout', [AuthController::class, 'logout'])->middleware('auth')->name('hotel.logout');

// ========== ADMIN AUTHENTICATION ROUTES ==========
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ========== PROTECTED ADMIN ROUTES ==========
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Hotel Management
    Route::get('/hotels/pending', [AdminDashboardController::class, 'pendingHotels'])->name('hotels.pending');
    Route::get('/hotels', [AdminDashboardController::class, 'allHotels'])->name('hotels.all');
    Route::get('/hotels/{id}', [AdminDashboardController::class, 'showHotelDetails'])->name('hotels.details');
    
    // Approval Actions
    Route::post('/hotels/{id}/approve', [AdminDashboardController::class, 'approveHotel'])->name('hotels.approve');
    Route::post('/hotels/{id}/reject', [AdminDashboardController::class, 'rejectHotel'])->name('hotels.reject');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'allUsers'])->name('users');
    
    // Booking Management
    Route::get('/bookings', [AdminDashboardController::class, 'allBookings'])->name('bookings');
});

// ========== PROTECTED OWNER ROUTES ==========
Route::middleware(['owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    
    // Hotel Profile
    Route::get('/hotel-profile', [OwnerDashboardController::class, 'hotelProfile'])->name('hotel-profile');
    Route::post('/hotel-profile', [OwnerDashboardController::class, 'updateHotelProfile'])->name('hotel-profile.update');
    
    // Staff Management
    Route::get('/staff', [OwnerDashboardController::class, 'manageStaff'])->name('staff');
    Route::get('/staff/create', [OwnerDashboardController::class, 'showCreateStaffForm'])->name('staff.create-form');
    Route::post('/staff/create', [OwnerDashboardController::class, 'createStaff'])->name('staff.create');
    Route::delete('/staff/{id}', [OwnerDashboardController::class, 'deleteStaff'])->name('staff.delete');
    
    // Room Management
    Route::get('/rooms', [OwnerDashboardController::class, 'manageRooms'])->name('rooms');
    
    // Bookings
    Route::get('/bookings', [OwnerDashboardController::class, 'viewBookings'])->name('bookings');
    
    // Reports
    Route::get('/reports', [OwnerDashboardController::class, 'viewReports'])->name('reports');
});

// ========== PROTECTED MANAGER ROUTES ==========
Route::middleware(['manager'])->prefix('manager')->name('manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    
    // Room Management
    Route::get('/rooms', [ManagerDashboardController::class, 'manageRooms'])->name('rooms');
    Route::post('/rooms/{id}/pricing', [ManagerDashboardController::class, 'updateRoomPricing'])->name('rooms.pricing');
    Route::post('/rooms/{id}/availability', [ManagerDashboardController::class, 'updateRoomAvailability'])->name('rooms.availability');
    
    // Bookings
    Route::get('/bookings', [ManagerDashboardController::class, 'viewBookings'])->name('bookings');
    Route::post('/bookings/{id}/status', [ManagerDashboardController::class, 'updateBookingStatus'])->name('bookings.status');
});

// ========== PROTECTED RECEPTIONIST ROUTES ==========
Route::middleware(['receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::get('/bookings', [ReceptionistDashboardController::class, 'viewBookings'])->name('bookings');
    
    // Check-in/Check-out
    Route::post('/bookings/{id}/check-in', [ReceptionistDashboardController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{id}/check-out', [ReceptionistDashboardController::class, 'checkOut'])->name('bookings.checkout');
    
    // Arrivals & Departures
    Route::get('/arrivals', [ReceptionistDashboardController::class, 'todayArrivals'])->name('arrivals');
    Route::get('/departures', [ReceptionistDashboardController::class, 'todayDepartures'])->name('departures');
});
