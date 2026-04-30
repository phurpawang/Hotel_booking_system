<?php

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestBookingController;
use App\Http\Controllers\GuestInquiryController;
use App\Http\Controllers\GuestReviewController;
use App\Http\Controllers\HotelRegistrationController;
use App\Http\Controllers\AuthController;  // Changed from HotelAuthController
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ReceptionistDashboardController;
use App\Http\Controllers\HotelDeregistrationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ========== ADMIN AUTHENTICATION ROUTES ==========
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    
    // Hotels Management (CRUD)
    Route::resource('hotels', AdminHotelController::class);
    Route::post('/hotels/{id}/approve', [AdminHotelController::class, 'approve'])->name('hotels.approve');
    Route::post('/hotels/{id}/reject', [AdminHotelController::class, 'reject'])->name('hotels.reject');
    
    // Reservations Management
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{id}', [AdminReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('/reservations/{id}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');
    
    // Payments Management
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/mark-paid', [AdminPaymentController::class, 'markPaid'])->name('payments.mark-paid');
    Route::post('/payments/{id}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');
    
    // Commission Management
    Route::get('/commissions', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{payout}', [App\Http\Controllers\Admin\CommissionController::class, 'show'])->name('commissions.show');
    Route::post('/commissions/generate', [App\Http\Controllers\Admin\CommissionController::class, 'generatePayouts'])->name('commissions.generate');
    Route::get('/commissions/{payout}/payout', [App\Http\Controllers\Admin\CommissionController::class, 'payoutForm'])->name('commissions.payout-form');
    Route::post('/commissions/{payout}/mark-paid', [App\Http\Controllers\Admin\CommissionController::class, 'markAsPaid'])->name('commissions.mark-paid');
    Route::get('/commissions/{payout}/offline-commission', [App\Http\Controllers\Admin\CommissionController::class, 'offlineCommissionForm'])->name('commissions.offline-commission-form');
    Route::post('/commissions/{payout}/offline-commission', [App\Http\Controllers\Admin\CommissionController::class, 'markOfflineCommissionAsReceived'])->name('commissions.mark-offline-commission');
    Route::get('/commissions-report/earnings', [App\Http\Controllers\Admin\CommissionController::class, 'earnings'])->name('commissions.earnings');
    Route::get('/commissions-report/hotels', [App\Http\Controllers\Admin\CommissionController::class, 'hotelReport'])->name('commissions.hotel-report');
    
    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    
    // Reviews Management
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{id}/status', [App\Http\Controllers\Admin\ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/statistics', [App\Http\Controllers\Admin\ReviewController::class, 'statistics'])->name('reviews.statistics');
    
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});

// Guest Routes (Home & Booking)
Route::get('/', [GuestBookingController::class, 'index'])->name('home');
Route::get('/search', [GuestBookingController::class, 'search'])->name('guest.search');

// ===== DATE PICKER TEST PAGE (TEMPORARY) =====
Route::get('/test/date-picker', function () {
    return view('example-date-picker');
});

// AJAX Filter API Route
Route::post('/api/hotels/filter', [\App\Http\Controllers\HotelFilterController::class, 'applyFilters'])->name('api.hotels.filter');

// Redirect default /login to hotel login
Route::get('/login', function () {
    return redirect()->route('hotel.login');
});

// Hotel Registration (must be before /hotel/{id})
Route::get('/hotel/register', [HotelRegistrationController::class, 'showRegistrationForm'])->name('hotel.register');
Route::post('/hotel/register', [HotelRegistrationController::class, 'register'])->name('hotel.register.submit');
Route::get('/hotel/registration-success', [HotelRegistrationController::class, 'registrationSuccess'])->name('hotel.registration.success');

// Hotel Login (must be before /hotel/{id}) - Using new password-based AuthController
Route::get('/hotel/login', [AuthController::class, 'showLogin'])->name('hotel.login');
Route::post('/hotel/login', [AuthController::class, 'login'])->name('hotel.login.submit');
Route::post('/hotel/logout', [AuthController::class, 'logout'])->name('hotel.logout');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// Hotel Check Status (must be before /hotel/{id})
Route::get('/hotel/check-status', [HotelRegistrationController::class, 'showCheckStatusForm'])->name('hotel.check-status');
Route::post('/hotel/check-status', [HotelRegistrationController::class, 'checkStatus'])->name('hotel.check-status.submit');

// Guest hotel viewing (must be after specific routes)
Route::get('/hotel/{id}', [GuestBookingController::class, 'showHotel'])->name('guest.hotel');
Route::post('/hotel/{id}/inquiry', [GuestInquiryController::class, 'store'])->name('guest.inquiry.store');

Route::get('/booking/form', [GuestBookingController::class, 'showBookingForm'])->name('guest.booking.form');
Route::post('/booking/confirm', [GuestBookingController::class, 'confirmBooking'])->name('guest.booking.confirm');
Route::get('/booking/confirmation/{booking_id}', [GuestBookingController::class, 'showConfirmation'])->name('guest.booking.confirmation');

// Manage Booking (No Login Required)
Route::get('/manage-booking', [GuestBookingController::class, 'showManageBookingForm'])->name('guest.manage-booking');
Route::match(['get', 'post'], '/manage-booking/view', [GuestBookingController::class, 'viewBooking'])->name('guest.booking.view');
Route::post('/booking/{booking_id}/cancel', [GuestBookingController::class, 'cancelBooking'])->name('guest.booking.cancel');

// Guest Reviews (No Login Required) - Unauthenticated
Route::get('/booking/{booking_id}/review', [GuestBookingController::class, 'showReviewForm'])->name('guest.review.form');
Route::post('/booking/review/submit', [GuestBookingController::class, 'submitReview'])->name('guest.review.submit');

// ========== RESTRUCTURED OWNER/MANAGER/RECEPTIONIST DASHBOARD ROUTES ==========

// Owner Dashboard & Routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    
    // Staff Management (Full CRUD)
    Route::resource('staff', App\Http\Controllers\Owner\StaffController::class);
    
    // Reviews Management
    Route::get('/reviews', [App\Http\Controllers\Owner\ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [App\Http\Controllers\Owner\ReviewManagementController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{id}/reply', [App\Http\Controllers\Owner\ReviewManagementController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{id}', [App\Http\Controllers\Owner\ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
    
    // Guest Inquiries/Questions
    Route::resource('inquiries', App\Http\Controllers\Owner\InquiryController::class);
    Route::post('/inquiries/{id}/answer', [App\Http\Controllers\Owner\InquiryController::class, 'answer'])->name('inquiries.answer');
    Route::post('/inquiries/{id}/close', [App\Http\Controllers\Owner\InquiryController::class, 'close'])->name('inquiries.close');
    
    // Amenities (Full CRUD)
    Route::resource('amenities', App\Http\Controllers\Owner\AmenityController::class);
    
    // Promotions (Full CRUD)
    Route::resource('promotions', App\Http\Controllers\Owner\PromotionController::class);
    
    // Payments
    Route::get('/payments', [App\Http\Controllers\Owner\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [App\Http\Controllers\Owner\PaymentController::class, 'show'])->name('payments.show');
    
    // Revenue & Commission Tracking
    Route::get('/revenue', [App\Http\Controllers\Owner\RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/{payout}', [App\Http\Controllers\Owner\RevenueController::class, 'show'])->name('revenue.show');
    Route::get('/revenue/report/monthly', [App\Http\Controllers\Owner\RevenueController::class, 'monthlyReport'])->name('revenue.monthly-report');
    
    // Guests
    Route::get('/guests', [App\Http\Controllers\Owner\GuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/{email}', [App\Http\Controllers\Owner\GuestController::class, 'show'])->name('guests.show');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Owner\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Owner\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Owner\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    // AJAX Endpoints for real-time updates
    Route::get('/notifications/ajax/unread-count', [App\Http\Controllers\Owner\NotificationController::class, 'getUnreadCount'])->name('notifications.ajax.unreadCount');
    Route::get('/notifications/ajax/recent', [App\Http\Controllers\Owner\NotificationController::class, 'getRecent'])->name('notifications.ajax.recent');
    
    // Reservations (Full CRUD)
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'checkIn'])->name('reservations.checkin');
    Route::post('/reservations/{id}/checkout', [ReservationController::class, 'checkOut'])->name('reservations.checkout');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{id}/mark-paid', [ReservationController::class, 'markAsPaid'])->name('reservations.mark-paid');
    
    // Rooms (Full CRUD)
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{id}/toggle-availability', [RoomController::class, 'toggleAvailability'])->name('rooms.toggle');
    Route::post('/rooms/{id}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.status');
    Route::get('/rooms/{id}/availability', [RoomController::class, 'availability'])->name('rooms.availability');
    Route::delete('/rooms/{id}/delete-photo', [RoomController::class, 'deletePhoto'])->name('rooms.delete-photo');
    
    // Reports (Full Access with Revenue)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-revenue', [ReportController::class, 'exportRevenue'])->name('reports.export.revenue');
    Route::get('/reports/export-bookings', [ReportController::class, 'exportBookings'])->name('reports.export.bookings');
    Route::get('/reports/export-occupancy', [ReportController::class, 'exportOccupancy'])->name('reports.export.occupancy');
    
    // Property Settings
    Route::get('/property/edit', [App\Http\Controllers\Owner\PropertySettingsController::class, 'edit'])->name('property.edit');
    Route::post('/property/update', [App\Http\Controllers\Owner\PropertySettingsController::class, 'update'])->name('property.update');
    
    // Deregistration Requests
    Route::get('/deregistration', [App\Http\Controllers\Owner\DeregistrationRequestController::class, 'index'])->name('deregistration.index');
    Route::post('/deregistration', [App\Http\Controllers\Owner\DeregistrationRequestController::class, 'store'])->name('deregistration.store');
    Route::patch('/deregistration/{id}/cancel', [App\Http\Controllers\Owner\DeregistrationRequestController::class, 'cancel'])->name('deregistration.cancel');
    
    // Legacy Routes (for backwards compatibility)
    Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/rates', [OwnerDashboardController::class, 'rates'])->name('rates');
    Route::get('/settings', [OwnerDashboardController::class, 'settings'])->name('settings');
});

// Manager Dashboard & Routes
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    
    // Reservations (CRUD, no delete)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'checkIn'])->name('reservations.checkin');
    Route::post('/reservations/{id}/checkout', [ReservationController::class, 'checkOut'])->name('reservations.checkout');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{id}/mark-paid', [ReservationController::class, 'markAsPaid'])->name('reservations.mark-paid');
    
    // Rooms (CRUD except delete)
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::post('/rooms/{id}/toggle-availability', [RoomController::class, 'toggleAvailability'])->name('rooms.toggle');
    Route::post('/rooms/{id}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.status');
    Route::get('/rooms/{id}/availability', [RoomController::class, 'availability'])->name('rooms.availability');
    Route::delete('/rooms/{id}/delete-photo', [RoomController::class, 'deletePhoto'])->name('rooms.delete-photo');
    
    // Reports (Limited - No Revenue Data)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-bookings', [ReportController::class, 'exportBookings'])->name('reports.export.bookings');
    Route::get('/reports/export-occupancy', [ReportController::class, 'exportOccupancy'])->name('reports.export.occupancy');
    
    // Property Settings
    Route::get('/property/edit', [\App\Http\Controllers\Manager\PropertySettingsController::class, 'edit'])->name('property.edit');
    Route::put('/property/update', [\App\Http\Controllers\Manager\PropertySettingsController::class, 'update'])->name('property.update');
    
    // Guest Inquiries/Questions - SHARED WITH OWNER
    Route::resource('inquiries', \App\Http\Controllers\Manager\InquiryController::class);
    
    // Messages (Legacy - kept for backward compatibility)
    Route::get('/messages', [\App\Http\Controllers\Manager\MessageController::class, 'index'])->name('messages.index');
    Route::patch('/messages/{id}/mark-as-read', [\App\Http\Controllers\Manager\MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::delete('/messages/{id}', [\App\Http\Controllers\Manager\MessageController::class, 'destroy'])->name('messages.destroy');
    
    // Deregistration Requests
    Route::get('/deregistration', [\App\Http\Controllers\Manager\DeregistrationRequestController::class, 'index'])->name('deregistration.index');
    Route::post('/deregistration', [\App\Http\Controllers\Manager\DeregistrationRequestController::class, 'store'])->name('deregistration.store');
    Route::patch('/deregistration/{id}/cancel', [\App\Http\Controllers\Manager\DeregistrationRequestController::class, 'cancel'])->name('deregistration.cancel');
    
    // Room Status Management (Manager can update room status)
    Route::get('/room-status', [\App\Http\Controllers\Manager\RoomStatusController::class, 'index'])->name('room-status.index');
    Route::get('/room-status/{id}', [\App\Http\Controllers\Manager\RoomStatusController::class, 'show'])->name('room-status.show');
    Route::post('/room-status/{id}', [\App\Http\Controllers\Manager\RoomStatusController::class, 'updateStatus'])->name('room-status.update');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Manager\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Manager\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Manager\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    // AJAX Endpoints for real-time updates
    Route::get('/notifications/ajax/unread-count', [\App\Http\Controllers\Manager\NotificationController::class, 'getUnreadCount'])->name('notifications.ajax.unreadCount');
    Route::get('/notifications/ajax/recent', [\App\Http\Controllers\Manager\NotificationController::class, 'getRecent'])->name('notifications.ajax.recent');
    
    // Guest Reviews Management
    Route::get('/reviews', [\App\Http\Controllers\Manager\ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [\App\Http\Controllers\Manager\ReviewManagementController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{id}/reply', [\App\Http\Controllers\Manager\ReviewManagementController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\Manager\ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
    
    // Legacy Routes
    Route::get('/bookings', [ManagerDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/rates', [ManagerDashboardController::class, 'rates'])->name('rates');
});

// Receptionist Dashboard & Routes
Route::middleware(['auth', 'receptionist'])->prefix('reception')->name('reception.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
    
    // Reservations (View, Create, Check-in/out Only)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{id}/checkin', [ReservationController::class, 'checkIn'])->name('reservations.checkin');
    Route::post('/reservations/{id}/checkout', [ReservationController::class, 'checkOut'])->name('reservations.checkout');
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{id}/mark-paid', [ReservationController::class, 'markAsPaid'])->name('reservations.mark-paid');
    
    // Rooms (View Only)
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/rooms/{id}/availability', [RoomController::class, 'availability'])->name('rooms.availability');
    
    // Legacy Check-in/out Routes - REMOVED (use Bookings section instead)
    // Route::get('/bookings', [ReceptionistDashboardController::class, 'bookings'])->name('bookings');
    // Route::get('/checkin', [ReceptionistDashboardController::class, 'checkinList'])->name('checkin');
    // Route::post('/checkin/{id}', [ReceptionistDashboardController::class, 'processCheckin'])->name('checkin.process');
    // Route::get('/checkout', [ReceptionistDashboardController::class, 'checkoutList'])->name('checkout');
    // Route::post('/checkout/{id}', [ReceptionistDashboardController::class, 'processCheckout'])->name('checkout.process');
    
    // Guests Management
    Route::get('/guests', [\App\Http\Controllers\Reception\GuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/create', [\App\Http\Controllers\Reception\GuestController::class, 'create'])->name('guests.create');
    Route::post('/guests', [\App\Http\Controllers\Reception\GuestController::class, 'store'])->name('guests.store');
    Route::get('/guests/{id}', [\App\Http\Controllers\Reception\GuestController::class, 'show'])->name('guests.show');
    Route::get('/guests/{id}/edit', [\App\Http\Controllers\Reception\GuestController::class, 'edit'])->name('guests.edit');
    Route::put('/guests/{id}', [\App\Http\Controllers\Reception\GuestController::class, 'update'])->name('guests.update');
    
    // Room Status (View Only - Reception cannot update status, only Manager can)
    // Route::get('/room-status', [\App\Http\Controllers\Reception\RoomStatusController::class, 'index'])->name('room-status.index');
    // Route::get('/room-status/{id}', [\App\Http\Controllers\Reception\RoomStatusController::class, 'show'])->name('room-status.show');
    // Status updates removed - Manager only feature
    
    // Payments
    Route::get('/payments', [\App\Http\Controllers\Reception\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [\App\Http\Controllers\Reception\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [\App\Http\Controllers\Reception\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [\App\Http\Controllers\Reception\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/status', [\App\Http\Controllers\Reception\PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Reception\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Reception\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Reception\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Reception\NotificationController::class, 'destroy'])->name('notifications.destroy');
    // AJAX Endpoints for real-time updates
    Route::get('/notifications/ajax/unread-count', [\App\Http\Controllers\Reception\NotificationController::class, 'getUnreadCount'])->name('notifications.ajax.unreadCount');
    Route::get('/notifications/ajax/recent', [\App\Http\Controllers\Reception\NotificationController::class, 'getRecent'])->name('notifications.ajax.recent');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Reception\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Reception\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Reception\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [\App\Http\Controllers\Reception\ProfileController::class, 'uploadPhoto'])->name('profile.photo');
});

// Role-Based Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {

    // ========== OLD ADMIN ROUTES (COMMENTED OUT - Using new session-based admin system) ==========
    /*
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        // Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard'); // OLD - Replaced by new admin auth system
        
        // Hotel Registration Approvals
        Route::get('/pending-registrations', [AdminController::class, 'pendingRegistrations'])->name('pending-registrations');
        Route::get('/hotel/{id}/details', [AdminController::class, 'viewHotelDetails'])->name('hotel.details');
        Route::post('/hotel/{id}/approve', [AdminController::class, 'approveHotel'])->name('hotel.approve');
        Route::post('/hotel/{id}/reject', [AdminController::class, 'rejectHotel'])->name('hotel.reject');
        
        // All Hotels Management
        Route::get('/hotels', [AdminController::class, 'allHotels'])->name('hotels');
        Route::post('/hotel/{id}/suspend', [AdminController::class, 'suspendHotel'])->name('hotel.suspend');
        Route::post('/hotel/{id}/force-deregister', [AdminController::class, 'forceDeregister'])->name('hotel.force-deregister');
        
        // Deregistration Requests
        Route::get('/pending-deregistrations', [AdminController::class, 'pendingDeregistrations'])->name('pending-deregistrations');
        Route::get('/deregistration/{id}/details', [AdminController::class, 'viewDeregistrationDetails'])->name('deregistration.details');
        Route::post('/deregistration/{id}/approve', [AdminController::class, 'approveDeregistration'])->name('deregistration.approve');
        Route::post('/deregistration/{id}/reject', [AdminController::class, 'rejectDeregistration'])->name('deregistration.reject');
        
        // Bookings Management
        Route::get('/bookings', [AdminController::class, 'allBookings'])->name('bookings');
        
        Route::get('/reports', function () {
            return view('admin.reports');
        })->name('reports');
        
        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');
        
        // Hotel approval actions
        Route::post('/hotel/{id}/approve', [DashboardController::class, 'approveHotel'])->name('hotel.approve');
        Route::post('/hotel/{id}/reject', [DashboardController::class, 'rejectHotel'])->name('hotel.reject');
    });
    */

    // ========== HOTEL OWNER/MANAGER/RECEPTION ROUTES ==========
    // Note: Dashboard routes are now defined separately for each role
    // - Owner: /owner/dashboard, /owner/* routes
    // - Manager: /manager/dashboard, /manager/* routes  
    // - Reception: /reception/dashboard, /reception/* routes
    // Legacy shared routes via HotelDashboardController have been removed

    // Legacy routes DISABLED - Using new restructured routes above
    /*
    // ========== OWNER ROUTES ==========
    Route::middleware(['role:OWNER'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/hotel', function () {
            return view('owner.hotel');
        })->name('hotel');
        
        Route::get('/rooms', function () {
            return view('owner.rooms');
        })->name('rooms');
        
        Route::get('/bookings', function () {
            return view('owner.bookings');
        })->name('bookings');
        
        Route::get('/finance', function () {
            return view('owner.finance');
        })->name('finance');
        
        Route::get('/staff', function () {
            return view('owner.staff');
        })->name('staff');
        
        Route::get('/settings', function () {
            return view('owner.settings');
        })->name('settings');
    });
    */

    // Legacy manager and reception routes DISABLED - Using new restructured routes above
    /*
    // ========== MANAGER ROUTES ==========
    Route::middleware(['role:MANAGER,OWNER'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/bookings', function () {
            return view('manager.bookings');
        })->name('bookings');
        
        Route::get('/rooms', function () {
            return view('manager.rooms');
        })->name('rooms');
        
        Route::get('/pricing', function () {
            return view('manager.pricing');
        })->name('pricing');
        
        Route::get('/guests', function () {
            return view('manager.guests');
        })->name('guests');
        
        Route::get('/reports', function () {
            return view('manager.reports');
        })->name('reports');
    });

    // ========== RECEPTION ROUTES ==========
    Route::middleware(['role:RECEPTION,MANAGER,OWNER'])->prefix('reception')->name('reception.')->group(function () {
        Route::get('/arrivals', function () {
            return view('reception.arrivals');
        })->name('arrivals');
        
        Route::get('/departures', function () {
            return view('reception.departures');
        })->name('departures');
        
        Route::get('/checkin', function () {
            return view('reception.checkin');
        })->name('checkin');
        
        Route::get('/checkout', function () {
            return view('reception.checkout');
        })->name('checkout');
        
        Route::get('/guests', function () {
            return view('reception.guests');
        })->name('guests');
    });
    */

    // ========== BOOKING STATUS UPDATE (Manager, Reception, Owner) ==========
    Route::middleware(['role:RECEPTION,MANAGER,OWNER'])->group(function () {
        Route::patch('/booking/{id}/status', [DashboardController::class, 'updateBookingStatus'])
            ->name('booking.status');
    });
});

// Laravel auth routes (required for logout and password management)
require __DIR__.'/auth.php';

// ========== EMAIL TESTING ROUTE (FOR DEBUGGING ONLY - REMOVE IN PRODUCTION) ==========
Route::get('/test-email', function () {
    try {
        // Test 1: Simple raw email
        Mail::raw('This is a test email from BHBS', function ($message) {
            $message->to('test@example.com')
                    ->subject('BHBS - Test Email');
        });

        $results = [
            'status' => 'success',
            'message' => 'Test email sent successfully!',
            'mail_config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ],
            'note' => 'Check storage/logs/laravel.log for detailed logs'
        ];

        Log::info('Test email sent successfully', $results);

        return response()->json($results);

    } catch (\Exception $e) {
        $error = [
            'status' => 'error',
            'message' => 'Failed to send test email',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'mail_config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
            ]
        ];

        Log::error('Test email failed', $error);

        return response()->json($error, 500);
    }
})->name('test.email');
