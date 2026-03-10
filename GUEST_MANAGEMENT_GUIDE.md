# Guest Management System - Complete Implementation Guide

## Overview
This guide documents the complete Guest Management System that ensures **guests remain in the system permanently** even after check-out, with full booking history tracking for reporting, repeat bookings, and marketing purposes.

---

## 1. Database Structure

### Users Table (Guests)
Guests are stored in the `users` table with `role = 'GUEST'`. The following fields are available:

```sql
- id (Primary Key)
- name (Guest full name)
- email (Unique email address)
- phone (Contact phone number)
- mobile (Alternative mobile number)
- address (Guest address)
- profile_photo (Profile picture path)
- password (Hashed password, default: 'guest123')
- role (Set to 'GUEST')
- hotel_id (Associated hotel - nullable for guests)
- created_by (Staff who created the guest record)
- created_at (First registration date)
- updated_at (Last profile update)
```

### Bookings Table
Each booking is linked to a guest via `user_id`:

```sql
- id (Primary Key)
- booking_id (Unique booking reference, e.g., BK-1234)
- user_id (Foreign key to users.id - THE GUEST)
- hotel_id (Foreign key to hotels.id)
- room_id (Foreign key to rooms.id)
- guest_name (Cached guest name)
- guest_email (Cached guest email)
- guest_phone (Cached guest phone)
- check_in_date (Planned check-in date)
- check_out_date (Planned check-out date)
- actual_check_in (Actual check-in timestamp)
- actual_check_out (Actual check-out timestamp)
- status (CONFIRMED, CHECKED_IN, CHECKED_OUT, CANCELLED)
- payment_status (PENDING, PAID, REFUNDED, FAILED)
- total_price (Booking total amount)
- num_guests (Number of guests)
- special_requests (Guest requests)
- created_at (Booking creation date)
- updated_at (Last update)
```

---

## 2. Laravel Models & Relationships

### User Model (Guest)
Location: `app/Models/User.php`

#### Key Relationships
```php
/**
 * Get all bookings made by this user/guest
 */
public function bookings()
{
    return $this->hasMany(Booking::class, 'user_id');
}

/**
 * Get the last completed booking (checked out)
 */
public function lastCompletedBooking()
{
    return $this->hasOne(Booking::class, 'user_id')
        ->where('status', 'CHECKED_OUT')
        ->latest('actual_check_out');
}
```

#### Helper Methods
```php
/**
 * Get total number of bookings for this guest
 */
public function getTotalBookingsAttribute()
{
    return $this->bookings()->count();
}

/**
 * Get last visit date (last check-out date)
 */
public function getLastVisitDateAttribute()
{
    $lastBooking = $this->lastCompletedBooking;
    return $lastBooking ? $lastBooking->actual_check_out : null;
}

/**
 * Check if user is a guest
 */
public function isGuest()
{
    return strtoupper($this->role) === 'GUEST';
}
```

### Booking Model
Location: `app/Models/Booking.php`

#### Key Relationships
```php
/**
 * Get the user who made the booking (THE GUEST)
 */
public function user()
{
    return $this->belongsTo(User::class);
}

/**
 * Get the room for this booking
 */
public function room()
{
    return $this->belongsTo(Room::class);
}

/**
 * Get the hotel for this booking
 */
public function hotel()
{
    return $this->belongsTo(Hotel::class);
}
```

#### Query Scopes
```php
// Get checked out bookings
Booking::checkedOut()->get();

// Get completed bookings (alias for checked out)
Booking::completed()->get();

// Get confirmed bookings
Booking::confirmed()->get();

// Get checked in bookings
Booking::checkedIn()->get();

// Get today's arrivals
Booking::todayArrivals()->get();

// Get today's departures
Booking::todayDepartures()->get();
```

---

## 3. Guest Controller
Location: `app/Http/Controllers/Reception/GuestController.php`

### Available Methods

#### 1. List All Guests (`index`)
**Route:** `GET /reception/guests`
**Purpose:** Display all guests who have made bookings at the current hotel

```php
public function index(Request $request)
{
    // Shows guests with:
    // - Guest Name
    // - Phone
    // - Email
    // - Total Bookings Count
    // - Last Visit Date
    // - Search functionality
}
```

**Features:**
- Search by name, email, or phone
- Shows booking count per guest
- Shows last check-out date
- Pagination (15 guests per page)
- Only shows guests who have bookings at current hotel

#### 2. View Guest Details (`show`)
**Route:** `GET /reception/guests/{id}`
**Purpose:** Display complete guest profile with booking history

```php
public function show($id)
{
    // Shows:
    // - Guest profile information
    // - Statistics (total bookings, completed, upcoming)
    // - Complete booking history table
    // - Pagination for bookings (10 per page)
}
```

**Statistics Displayed:**
- Total Bookings: All bookings count
- Completed Stays: CHECKED_OUT bookings
- Upcoming Bookings: CONFIRMED and CHECKED_IN bookings

**Booking History Table:**
- Booking ID
- Room Number
- Check-in Date
- Check-out Date
- Status (with color-coded badges)
- Total Amount

#### 3. Add New Guest (`create`, `store`)
**Route:** `GET /reception/guests/create` (form)
**Route:** `POST /reception/guests` (submit)

```php
public function store(Request $request)
{
    // Creates new guest with:
    // - name (required)
    // - email (required, unique)
    // - phone (required)
    // - address (optional)
    // - Default password: 'guest123'
    // - Role: 'GUEST'
}
```

**Validation Rules:**
- Name: required, string, max 255 characters
- Email: required, email format, unique in users table
- Phone: required, string, max 20 characters
- Address: optional, text, max 500 characters

#### 4. Edit Guest (`edit`, `update`)
**Route:** `GET /reception/guests/{id}/edit` (form)
**Route:** `PUT /reception/guests/{id}` (submit)

```php
public function update(Request $request, $id)
{
    // Updates guest information
    // - name, email, phone, address
    // - Email uniqueness check (ignores current guest)
}
```

---

## 4. Routes Configuration
Location: `routes/web.php`

```php
Route::middleware(['auth', 'receptionist'])
    ->prefix('reception')
    ->name('reception.')
    ->group(function () {
    
    // Guest Management Routes
    Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/create', [GuestController::class, 'create'])->name('guests.create');
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
    Route::get('/guests/{id}', [GuestController::class, 'show'])->name('guests.show');
    Route::get('/guests/{id}/edit', [GuestController::class, 'edit'])->name('guests.edit');
    Route::put('/guests/{id}', [GuestController::class, 'update'])->name('guests.update');
});
```

---

## 5. Blade Views

### Guest List View
Location: `resources/views/reception/guests/index.blade.php`

**Features:**
- Search bar (searches name, email, phone)
- Responsive table with columns:
  - Guest Name (with avatar circle)
  - Phone
  - Email
  - Total Bookings (badge)
  - Last Visit Date
  - Actions (View, Edit buttons)
- Pagination with search preservation
- Empty state message
- "Add New Guest" button

**Key Code Snippet:**
```blade
@forelse($guests as $guest)
<tr>
    <td>{{ $guest->name }}</td>
    <td>{{ $guest->phone }}</td>
    <td>{{ $guest->email }}</td>
    <td>{{ $guest->bookings_count }} bookings</td>
    <td>
        @if($guest->bookings->first() && $guest->bookings->first()->actual_check_out)
            {{ $guest->bookings->first()->actual_check_out->format('M d, Y') }}
        @else
            Never
        @endif
    </td>
    <td>
        <a href="{{ route('reception.guests.show', $guest->id) }}">View</a>
        <a href="{{ route('reception.guests.edit', $guest->id) }}">Edit</a>
    </td>
</tr>
@empty
<tr><td colspan="6">No guests found</td></tr>
@endforelse
```

### Guest Details View
Location: `resources/views/reception/guests/show.blade.php`

**Features:**
- Guest profile header with large avatar
- Three statistics cards:
  - Total Bookings (green)
  - Completed Stays (purple)
  - Upcoming Bookings (blue)
- Complete booking history table
- Status badges (color-coded by booking status)
- Pagination for booking history
- Back to list and Edit buttons

**Booking History Table:**
```blade
<table>
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Room</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>#{{ $booking->booking_id }}</td>
            <td>{{ $booking->room->room_number }}</td>
            <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
            <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
            <td>
                @if($booking->status == 'CHECKED_OUT')
                    <span class="badge-green">Checked Out</span>
                @elseif($booking->status == 'CHECKED_IN')
                    <span class="badge-blue">Checked In</span>
                @elseif($booking->status == 'CONFIRMED')
                    <span class="badge-yellow">Confirmed</span>
                @endif
            </td>
            <td>Nu. {{ number_format($booking->total_price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```

### Create Guest View
Location: `resources/views/reception/guests/create.blade.php`

**Form Fields:**
- Name (text input, required)
- Email (email input, required, unique)
- Phone (text input, required)
- Address (textarea, optional)

**Info Note:**
"Default password for new guests is 'guest123'. Guests can change this later."

### Edit Guest View
Location: `resources/views/reception/guests/edit.blade.php`

**Features:**
- Pre-populated form with existing guest data
- Same validation as create
- Uses `old()` helper for form repopulation on errors
- PUT method for RESTful update

---

## 6. Check-Out Process (Guests Remain in System)

### Location: `app/Http/Controllers/ReservationController.php`

#### Check-Out Method
```php
public function checkOut($id)
{
    $user = Auth::user();
    $booking = Booking::where('id', $id)
                     ->where('hotel_id', $user->hotel_id)
                     ->firstOrFail();

    if ($booking->status !== 'CHECKED_IN') {
        return redirect()->back()
            ->with('error', 'Only checked-in bookings can be checked out.');
    }

    try {
        DB::transaction(function() use ($booking) {
            // Update booking status
            $booking->update([
                'status' => 'CHECKED_OUT',
                'actual_check_out' => now(),
            ]);

            // Update room status to available
            $booking->room->update([
                'status' => 'AVAILABLE'
            ]);
            
            // IMPORTANT: Guest (user) is NOT deleted
            // Guest record remains in users table
            // Booking record remains with status = 'CHECKED_OUT'
        });

        return redirect()->back()
            ->with('success', 'Guest checked out successfully.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to check out guest.');
    }
}
```

### What Happens During Check-Out:
1. ✅ Booking status changes to `CHECKED_OUT`
2. ✅ `actual_check_out` timestamp is recorded
3. ✅ Room status changes to `AVAILABLE`
4. ✅ **Guest record REMAINS in users table**
5. ✅ **Booking record REMAINS in bookings table**
6. ✅ Guest is still visible in Guest section
7. ✅ Booking appears in guest's history

### What Does NOT Happen:
❌ Guest record is NOT deleted
❌ Booking record is NOT deleted
❌ Guest loses access to their data
❌ Booking history is NOT lost

---

## 7. Usage Examples & Queries

### Get All Guests at Current Hotel
```php
$guests = User::where('role', 'GUEST')
    ->whereHas('bookings', function ($q) use ($hotelId) {
        $q->where('hotel_id', $hotelId);
    })
    ->with(['bookings' => function ($q) use ($hotelId) {
        $q->where('hotel_id', $hotelId);
    }])
    ->get();
```

### Get Guest with Booking Count
```php
$guest = User::withCount(['bookings' => function ($q) use ($hotelId) {
    $q->where('hotel_id', $hotelId);
}])->findOrFail($guestId);

echo $guest->bookings_count; // Total bookings
```

### Get Guest's Last Visit Date
```php
$lastBooking = $guest->bookings()
    ->where('status', 'CHECKED_OUT')
    ->latest('actual_check_out')
    ->first();

$lastVisit = $lastBooking ? $lastBooking->actual_check_out : null;
```

### Get All Bookings for a Guest
```php
$bookings = Booking::where('hotel_id', $hotelId)
    ->where('user_id', $guestId)
    ->with('room')
    ->orderBy('created_at', 'desc')
    ->get();
```

### Get Guest Statistics
```php
$stats = [
    'total_bookings' => Booking::where('hotel_id', $hotelId)
        ->where('user_id', $guestId)
        ->count(),
        
    'completed' => Booking::where('hotel_id', $hotelId)
        ->where('user_id', $guestId)
        ->where('status', 'CHECKED_OUT')
        ->count(),
        
    'upcoming' => Booking::where('hotel_id', $hotelId)
        ->where('user_id', $guestId)
        ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
        ->count(),
];
```

### Search Guests
```php
$guests = User::where('role', 'GUEST')
    ->whereHas('bookings', function ($q) use ($hotelId) {
        $q->where('hotel_id', $hotelId);
    })
    ->where(function ($q) use ($search) {
        $q->where('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%")
          ->orWhere('phone', 'LIKE', "%{$search}%");
    })
    ->paginate(15);
```

---

## 8. Benefits of This System

### ✅ Guest Records Are Permanent
- Guests never lose their account after check-out
- Complete history is maintained forever
- Easy to track repeat customers

### ✅ Comprehensive Reporting
- View total bookings per guest
- Track guest visit frequency
- Identify VIP/frequent guests
- Calculate guest lifetime value

### ✅ Repeat Bookings
- Guest info is already in system
- Faster booking process for return guests
- Can view past preferences
- Can offer personalized service

### ✅ Marketing & Analytics
- Send targeted promotions to past guests
- Track seasonal guests
- Measure customer retention
- Identify booking patterns

### ✅ Data Integrity
- No data loss on check-out
- Full audit trail maintained
- Historical analysis possible
- Compliance with data retention

---

## 9. Key Features Summary

### Guest Management Features:
✅ Permanent guest records (never deleted)
✅ Complete booking history per guest
✅ Search guests by name, email, or phone
✅ View guest statistics (total bookings, completed stays, upcoming)
✅ Add new guests manually
✅ Edit guest information
✅ Track last visit date
✅ Count total bookings per guest
✅ Multi-hotel support (guests linked via bookings)
✅ Responsive design (mobile-friendly)
✅ Proper MVC structure
✅ Laravel best practices
✅ Validation on all forms
✅ Clean, documented code

### Database Features:
✅ User model with bookings relationship
✅ Booking model with user (guest) relationship
✅ Proper foreign key constraints
✅ Timestamps for audit trail
✅ Status tracking (CONFIRMED, CHECKED_IN, CHECKED_OUT, CANCELLED)
✅ Payment status tracking
✅ Actual vs. planned dates

### UI Features:
✅ Color-coded status badges
✅ Gradient statistics cards
✅ Search functionality with result preservation
✅ Pagination on all lists
✅ Empty state messages
✅ Action buttons (View, Edit, Add New)
✅ Responsive tables
✅ Clean, modern Tailwind CSS design

---

## 10. File Structure

```
app/
├── Models/
│   ├── User.php (Guest model with relationships)
│   └── Booking.php (Booking model with user relationship)
├── Http/
│   └── Controllers/
│       └── Reception/
│           └── GuestController.php (All guest management logic)
│
database/
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2024_01_02_000006_extend_users_table_for_hotel_owners.php
│   ├── 2026_03_08_153737_add_guest_fields_to_users_table.php
│   └── [bookings table migration]
│
resources/
└── views/
    └── reception/
        ├── partials/
        │   └── sidebar.blade.php (Reusable sidebar)
        └── guests/
            ├── index.blade.php (Guest list)
            ├── show.blade.php (Guest detail with booking history)
            ├── create.blade.php (Add new guest)
            └── edit.blade.php (Edit guest)
│
routes/
└── web.php (Guest routes configuration)
```

---

## 11. Testing Checklist

### Guest List Page
- [ ] Can view all guests at current hotel
- [ ] Search by name works
- [ ] Search by email works
- [ ] Search by phone works
- [ ] Booking count is accurate
- [ ] Last visit date shows correctly
- [ ] Pagination works
- [ ] "Add New Guest" button works
- [ ] View and Edit buttons work

### Guest Details Page
- [ ] Guest profile displays correctly
- [ ] Statistics cards show accurate data
- [ ] Booking history table loads
- [ ] Status badges are color-coded
- [ ] Pagination works for bookings
- [ ] Can navigate back to list
- [ ] Edit button works

### Add Guest
- [ ] Form validation works
- [ ] Required fields enforced
- [ ] Email uniqueness check works
- [ ] Guest created successfully
- [ ] Redirects to guest list
- [ ] Success message displayed

### Edit Guest
- [ ] Form pre-populated with data
- [ ] Validation works
- [ ] Email uniqueness ignores current guest
- [ ] Updates save successfully
- [ ] Redirects to guest list
- [ ] Success message displayed

### Check-Out Process
- [ ] Guest record NOT deleted after check-out
- [ ] Booking status changes to CHECKED_OUT
- [ ] actual_check_out timestamp recorded
- [ ] Guest still visible in Guest section
- [ ] Booking appears in guest's history
- [ ] Room becomes available
- [ ] Last visit date updates

---

## 12. Maintenance & Best Practices

### Regular Tasks:
1. Monitor guest data for duplicates
2. Clean up test guest accounts
3. Backup guest and booking data regularly
4. Review and update validation rules as needed

### Security Considerations:
1. Ensure only hotel staff can access guest data
2. Implement proper authorization checks
3. Use HTTPS in production
4. Sanitize search inputs
5. Implement rate limiting on guest creation

### Performance Optimization:
1. Use eager loading for relationships (`with()`)
2. Add indexes on frequently searched columns
3. Implement caching for popular queries
4. Paginate large result sets
5. Monitor query performance

### Future Enhancements:
1. Guest preferences/notes
2. Guest communication history
3. Loyalty points system
4. Birthday/anniversary tracking
5. Marketing preferences
6. Guest feedback/reviews
7. Export guest list to CSV/Excel
8. Bulk email to guests

---

## 13. Support & Documentation

### Need Help?
- Check this guide first
- Review the code comments
- Test on development environment
- Ask team members

### Contributing:
- Follow Laravel coding standards
- Write clear commit messages
- Add PHPDoc comments
- Test before committing
- Update this guide if making changes

---

**System Status: ✅ FULLY IMPLEMENTED AND OPERATIONAL**

**Last Updated:** March 8, 2026
**Version:** 1.0
**Maintained By:** Development Team
