# RESTRUCTURED ROUTES CONFIGURATION

## IMPORTANT: Add these routes to your routes/web.php file

```php
<?php

use App\Http\Controllers\GuestBookingController;
use App\Http\Controllers\HotelRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ReceptionistDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (PUBLIC ACCESS)
|--------------------------------------------------------------------------
*/
Route::get('/', [GuestBookingController::class, 'index'])->name('home');
Route::get('/search', [GuestBookingController::class, 'search'])->name('guest.search');
Route::get('/hotel/{id}', [GuestBookingController::class, 'showHotel'])->name('guest.hotel');

/*
|--------------------------------------------------------------------------
| HOTEL REGISTRATION (OWNER ONLY)
|--------------------------------------------------------------------------
*/
Route::get('/hotel/register', [HotelRegistrationController::class, 'showRegistrationForm'])->name('hotel.register');
Route::post('/hotel/register', [HotelRegistrationController::class, 'register'])->name('hotel.register.submit');
Route::get('/hotel/registration-success', [HotelRegistrationController::class, 'registrationSuccess'])->name('hotel.registration.success');
Route::get('/hotel/check-status', [HotelRegistrationController::class, 'showCheckStatusForm'])->name('hotel.check-status');
Route::post('/hotel/check-status', [HotelRegistrationController::class, 'checkStatus'])->name('hotel.check-status.submit');

/*
|--------------------------------------------------------------------------
| HOTEL LOGIN (ALL ROLES)
|--------------------------------------------------------------------------
*/
Route::get('/hotel/login', [AuthController::class, 'showLogin'])->name('hotel.login');
Route::post('/hotel/login', [AuthController::class, 'login'])->name('hotel.login.submit');
Route::post('/hotel/logout', [AuthController::class, 'logout'])->name('hotel.logout');

/*
|--------------------------------------------------------------------------
| OWNER DASHBOARD & ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    
    // Staff Management (Owner Only)
    Route::get('/staff', [OwnerDashboardController::class, 'manageStaff'])->name('staff');
    Route::post('/staff/create', [OwnerDashboardController::class, 'createStaff'])->name('staff.create');
    Route::delete('/staff/{id}', [OwnerDashboardController::class, 'deleteStaff'])->name('staff.delete');
    
    // Bookings
    Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])->name('bookings');
    
    // Rooms
    Route::get('/rooms', [OwnerDashboardController::class, 'rooms'])->name('rooms');
    
    // Rates & Availability
    Route::get('/rates', [OwnerDashboardController::class, 'rates'])->name('rates');
    
    // Reports
    Route::get('/reports', [OwnerDashboardController::class, 'reports'])->name('reports');
    
    // Settings
    Route::get('/settings', [OwnerDashboardController::class, 'settings'])->name('settings');
});

// Owner Dashboard Alternate Route
Route::middleware(['auth', 'owner'])->get('/hotel/dashboard/owner', [OwnerDashboardController::class, 'index'])->name('hotel.dashboard.owner');

/*
|--------------------------------------------------------------------------
| MANAGER DASHBOARD & ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::get('/bookings', [ManagerDashboardController::class, 'bookings'])->name('bookings');
    
    // Rooms
    Route::get('/rooms', [ManagerDashboardController::class, 'rooms'])->name('rooms');
    
    // Rates
    Route::get('/rates', [ManagerDashboardController::class, 'rates'])->name('rates');
    
    // Reports (Limited)
    Route::get('/reports', [ManagerDashboardController::class, 'reports'])->name('reports');
});

// Manager Dashboard Alternate Route
Route::middleware(['auth', 'manager'])->get('/hotel/dashboard/manager', [ManagerDashboardController::class, 'index'])->name('hotel.dashboard.manager');

/*
|--------------------------------------------------------------------------
| RECEPTION DASHBOARD & ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'receptionist'])->prefix('reception')->name('reception.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::get('/bookings', [ReceptionistDashboardController::class, 'bookings'])->name('bookings');
    
    // Check-in
    Route::get('/checkin', [ReceptionistDashboardController::class, 'checkinList'])->name('checkin');
    Route::post('/checkin/{id}', [ReceptionistDashboardController::class, 'processCheckin'])->name('checkin.process');
    
    // Check-out
    Route::get('/checkout', [ReceptionistDashboardController::class, 'checkoutList'])->name('checkout');
    Route::post('/checkout/{id}', [ReceptionistDashboardController::class, 'processCheckout'])->name('checkout.process');
});

// Reception Dashboard Alternate Route
Route::middleware(['auth', 'receptionist'])->get('/hotel/dashboard/reception', [ReceptionistDashboardController::class, 'index'])->name('hotel.dashboard.reception');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::resource('hotels', AdminHotelController::class);
    Route::post('/hotels/{id}/approve', [AdminHotelController::class, 'approve'])->name('hotels.approve');
    Route::post('/hotels/{id}/reject', [AdminHotelController::class, 'reject'])->name('hotels.reject');
});
```

## MIDDLEWARE REGISTRATION

Add these middleware aliases to `app/Http/Kernel.php` in the `$middlewareAliases` array:

```php
protected $middlewareAliases = [
    // ... existing middleware
    'owner' => \App\Http\Middleware\OwnerMiddleware::class,
    'manager' => \App\Http\Middleware\ManagerMiddleware::class,
    'receptionist' => \App\Http\Middleware\ReceptionistMiddleware::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

## CONTROLLER STUB METHODS

If the following controller methods don't exist, create them:

### ManagerDashboardController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;
        
        $totalBookings = Booking::where('hotel_id', $user->hotel_id)->count();
        $pendingBookings = Booking::where('hotel_id', $user->hotel_id)
                                  ->where('status', 'pending')
                                  ->count();
        
        $totalRooms = Room::where('hotel_id', $user->hotel_id)->count();
        $occupiedRooms = Room::where('hotel_id', $user->hotel_id)
                            ->where('status', 'occupied')
                            ->count();
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        
        $todayCheckIns = Booking::where('hotel_id', $user->hotel_id)
                               ->whereDate('check_in_date', now())
                               ->count();
        $todayCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                ->whereDate('check_out_date', now())
                                ->count();
        
        $recentBookings = Booking::where('hotel_id', $user->hotel_id)
                                ->with('room')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
        
        return view('manager.dashboard', compact(
            'hotel',
            'totalBookings',
            'pendingBookings',
            'occupancyRate',
            'availableRooms',
            'todayCheckIns',
            'todayCheckOuts',
            'recentBookings'
        ));
    }
    
    public function bookings()
    {
        // Implement bookings page
        return view('manager.bookings');
    }
    
    public function rooms()
    {
        // Implement rooms page
        return view('manager.rooms');
    }
    
    public function rates()
    {
        // Implement rates page
        return view('manager.rates');
    }
    
    public function reports()
    {
        // Implement reports page
        return view('manager.reports');
    }
}
```

### ReceptionistDashboardController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hotel = $user->hotel;
        
        $todayCheckIns = Booking::where('hotel_id', $user->hotel_id)
                               ->whereDate('check_in_date', now())
                               ->count();
        $todayCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                ->whereDate('check_out_date', now())
                                ->count();
        
        $pendingCheckIns = Booking::where('hotel_id', $user->hotel_id)
                                  ->whereDate('check_in_date', now())
                                  ->where('status', 'confirmed')
                                  ->count();
        $pendingCheckOuts = Booking::where('hotel_id', $user->hotel_id)
                                   ->whereDate('check_out_date', now())
                                   ->where('status', 'checked_in')
                                   ->count();
        
        $totalRooms = Room::where('hotel_id', $user->hotel_id)->count();
        $occupiedRooms = Room::where('hotel_id', $user->hotel_id)
                            ->where('status', 'occupied')
                            ->count();
        $availableRooms = $totalRooms - $occupiedRooms;
        
        $todayCheckInsList = Booking::where('hotel_id', $user->hotel_id)
                                    ->whereDate('check_in_date', now())
                                    ->with('room')
                                    ->orderBy('check_in_date', 'asc')
                                    ->get();
        
        $todayCheckOutsList = Booking::where('hotel_id', $user->hotel_id)
                                     ->whereDate('check_out_date', now())
                                     ->with('room')
                                     ->orderBy('check_out_date', 'asc')
                                     ->get();
        
        $recentBookings = Booking::where('hotel_id', $user->hotel_id)
                                ->with('room')
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
        
        return view('reception.dashboard', compact(
            'hotel',
            'todayCheckIns',
            'todayCheckOuts',
            'pendingCheckIns',
            'pendingCheckOuts',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'todayCheckInsList',
            'todayCheckOutsList',
            'recentBookings'
        ));
    }
    
    public function bookings()
    {
        // Implement bookings page
        return view('reception.bookings');
    }
    
    public function checkinList()
    {
        // Implement check-in list
        return view('reception.checkin');
    }
    
    public function processCheckin($id)
    {
        // Process check-in
    }
    
    public function checkoutList()
    {
        // Implement check-out list
        return view('reception.checkout');
    }
    
    public function processCheckout($id)
    {
        // Process check-out
    }
}
```

## COMPLETE IMPLEMENTATION CHECKLIST

- [x] Registration form updated (role dropdown removed)
- [x] Controller auto-assigns role=OWNER
- [x] Login system supports all roles
- [x] Middleware created (Owner, Manager, Receptionist)
- [x] Owner dashboard with Chart.js & FullCalendar
- [x] Staff management page for owners
- [x] Manager dashboard (no revenue/staff access)
- [x] Receptionist dashboard (simplified)
- [ ] Add routes to routes/web.php (use code above)
- [ ] Register middleware in Kernel.php
- [ ] Create ManagerDashboardController
- [ ] Create ReceptionistDashboardController
- [ ] Test complete flow

## KEY SECURITY FEATURES

✅ Password hashing with bcrypt
✅ CSRF protection on all forms
✅ Middleware role-based access control
✅ Email unique validation
✅ Password confirmation
✅ Case-insensitive role checking
✅ Database role consistency (uppercase)

## TESTING INSTRUCTIONS

1. **Register a Property**
   - Visit: http://127.0.0.1:8000/hotel/register
   - Fill owner name, email, password
   - Submit → Auto-assigned as OWNER

2. **Admin Approval**
   - Login as admin
   - Approve the hotel

3. **Owner Login**
   - Login with Hotel ID + Email + Password
   - Should redirect to `/owner/dashboard`
   - Access staff management
   - Create manager/receptionist accounts

4. **Manager Login**
   - Login with credentials created by owner
   - Should redirect to `/manager/dashboard`
   - Cannot access staff management or property settings

5. **Receptionist Login**
   - Login with credentials created by owner
   - Should redirect to `/reception/dashboard`
   - Simplified interface for check-in/check-out

## NEXT STEPS

To complete the system:

1. Copy the routes from this file to your `routes/web.php`
2. Create missing controller methods (ManagerDashboardController, ReceptionistDashboardController)
3. Register middleware in Kernel.php
4. Test each role's login and dashboard access
5. Verify staff creation works from owner dashboard
6. Ensure middleware blocks unauthorized access

All view files have been created with:
- Booking.com-style professional design
- Chart.js integration for analytics
- FullCalendar for availability
- Tailwind CSS styling
- Font Awesome icons
- Responsive layouts
