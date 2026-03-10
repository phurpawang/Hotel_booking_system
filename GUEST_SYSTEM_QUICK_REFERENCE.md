# Guest Management System - Quick Reference

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    GUEST MANAGEMENT SYSTEM                   │
└─────────────────────────────────────────────────────────────┘

┌──────────────┐                  ┌──────────────┐
│   User       │                  │   Booking    │
│  (Guest)     │◄─────────────────│              │
│              │  user_id         │              │
│  - id        │                  │  - id        │
│  - name      │      hasMany     │  - user_id   │
│  - email     │◄─────────────────│  - hotel_id  │
│  - phone     │     bookings     │  - room_id   │
│  - mobile    │                  │  - status    │
│  - address   │                  │  - check_in  │
│  - role      │                  │  - check_out │
│              │                  │  - actual_*  │
└──────────────┘                  └──────────────┘
       │                                 │
       │ belongsTo                       │ belongsTo
       │                                 │
       ▼                                 ▼
┌──────────────┐                  ┌──────────────┐
│   Hotel      │                  │    Room      │
│              │                  │              │
│  - id        │                  │  - id        │
│  - name      │                  │  - number    │
│  - hotel_id  │                  │  - status    │
└──────────────┘                  └──────────────┘
```

## Guest Lifecycle

```
NEW GUEST
   │
   ├─► Created manually by reception staff
   │   OR
   ├─► Auto-created during online booking
   │
   ▼
GUEST RECORD SAVED (role='GUEST')
   │
   ├─► Guest makes booking
   │
   ▼
BOOKING CREATED (status='CONFIRMED')
   │
   ├─► Guest checks in
   │
   ▼
BOOKING UPDATED (status='CHECKED_IN')
   │
   ├─► Guest checks out
   │
   ▼
BOOKING UPDATED (status='CHECKED_OUT')
   │
   ├─► ✅ Guest record REMAINS in database
   ├─► ✅ Booking record REMAINS in database
   ├─► ✅ Visible in Guest section
   └─► ✅ Available for future bookings
```

## Key Relationships

### User → Bookings (One to Many)
```php
$guest = User::find(1);
$bookings = $guest->bookings; // Get all bookings
$lastVisit = $guest->lastCompletedBooking; // Get last check-out
```

### Booking → User (Many to One)
```php
$booking = Booking::find(1);
$guest = $booking->user; // Get the guest who made booking
```

## Quick Actions

### View All Guests
```
URL: /reception/guests
Method: GET
Shows: List of all guests with booking counts
```

### View Guest Details
```
URL: /reception/guests/{id}
Method: GET
Shows: Guest profile + complete booking history
```

### Add New Guest
```
URL: /reception/guests/create
Method: GET (form), POST (submit)
Fields: name, email, phone, address
```

### Edit Guest
```
URL: /reception/guests/{id}/edit
Method: GET (form), PUT (submit)
Updates: name, email, phone, address
```

### Check-Out Process
```
When: Guest checks out from room
Action: Booking status → CHECKED_OUT
Result: 
  ✅ Guest remains in system
  ✅ Booking history preserved
  ✅ Room becomes available
  ✅ Last visit date updated
```

## Statistics Available

### Per Guest:
- Total Bookings (all time)
- Completed Stays (checked out)
- Upcoming Bookings (confirmed + checked in)
- Last Visit Date (last actual_check_out)

### Per Hotel:
- Total Guests (unique users with bookings)
- Active Guests (with upcoming bookings)
- Returning Guests (more than 1 booking)
- New Guests (first booking this month/year)

## Database Fields

### Users (Guests)
```
✓ id - Primary key
✓ name - Guest full name
✓ email - Unique email
✓ phone - Contact phone
✓ mobile - Alternative number
✓ address - Guest address
✓ profile_photo - Photo path
✓ role - 'GUEST'
✓ password - Hashed (default: 'guest123')
✓ created_at - Registration date
✓ updated_at - Last update
```

### Bookings
```
✓ id - Primary key
✓ booking_id - Unique ref (BK-XXXX)
✓ user_id - Guest ID (FK)
✓ hotel_id - Hotel ID (FK)
✓ room_id - Room ID (FK)
✓ check_in_date - Planned check-in
✓ check_out_date - Planned check-out
✓ actual_check_in - Real check-in time
✓ actual_check_out - Real check-out time
✓ status - CONFIRMED/CHECKED_IN/CHECKED_OUT
✓ payment_status - PENDING/PAID/REFUNDED
✓ total_price - Booking amount
✓ guest_name - Cached name
✓ guest_email - Cached email
✓ guest_phone - Cached phone
```

## Routes Summary

```php
GET    /reception/guests              → List all guests
GET    /reception/guests/create       → Show add guest form
POST   /reception/guests              → Store new guest
GET    /reception/guests/{id}         → Show guest details
GET    /reception/guests/{id}/edit    → Show edit form
PUT    /reception/guests/{id}         → Update guest
```

## Benefits

✅ **Never Lose Guest Data** - Records permanent after check-out
✅ **Complete History** - Track all past bookings per guest
✅ **Repeat Bookings** - Fast check-in for returning guests
✅ **Marketing** - Target past guests with promotions
✅ **Reporting** - Analyze guest patterns and loyalty
✅ **Revenue Insights** - Track guest lifetime value
✅ **Better Service** - Access past preferences and requests

## Important Notes

⚠️ **Guests are NEVER deleted during check-out**
⚠️ **Bookings are NEVER deleted, only status updated**
⚠️ **Each guest linked to hotel via bookings, not direct FK**
⚠️ **Use eager loading (.with()) to avoid N+1 queries**
⚠️ **Search functionality searches name, email, phone**
⚠️ **Pagination set to 15 guests per page**
⚠️ **Last visit = last actual_check_out timestamp**

## Migration Info

**Latest Migration:** `2026_03_08_153737_add_guest_fields_to_users_table.php`

**Added Fields:**
- phone (nullable string)
- address (nullable text)
- profile_photo (nullable string)

**Status:** ✅ Successfully migrated

---

**Full Documentation:** See GUEST_MANAGEMENT_GUIDE.md
**System Status:** ✅ FULLY OPERATIONAL
**Last Updated:** March 8, 2026
