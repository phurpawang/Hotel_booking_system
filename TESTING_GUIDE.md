# BHBS Testing Guide - Quick Start

## 🚀 Quick Testing Checklist

### 1. Initial Setup (5 minutes)
```bash
# Verify migration completed
php artisan migrate:status

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start development server
php artisan serve
```

---

## 2. Test User Credentials

### Login Credentials (from LOGIN_CREDENTIALS.md):
```
Owner Account:
Email: owner@hotel1.com
Password: password123

Manager Account:
Email: manager@hotel1.com
Password: password123

Receptionist Account:
Email: reception@hotel1.com
Password: password123
```

---

## 3. Testing Workflow (30 minutes)

### Step 1: Test Owner Access (10 minutes)

**Login:**
1. Go to: `http://localhost:8000/hotel/login`
2. Login as Owner (owner@hotel1.com / password123)
3. Should redirect to: `/owner/dashboard`

**Test Dashboard:**
- [ ] See 6-month revenue chart
- [ ] See 6-month booking chart
- [ ] Verify metrics: Total Rooms, Bookings, Staff
- [ ] Check Today's Check-ins/Check-outs count

**Test Room Management:**
1. Go to: `/owner/rooms`
2. Click "Create New Room"
3. Fill form:
   - Room Number: 301 (or any unique number)
   - Room Type: Deluxe
   - Price: 3500
   - Capacity: 3
   - Upload photo (optional)
4. Submit - Should see success message
5. Verify room appears in list

**Test Reservation Management:**
1. Go to: `/owner/reservations`
2. Click "Create Booking"
3. Fill form:
   - Guest Name: Test Guest
   - Email: test@test.com
   - Phone: 17123456
   - Select Room
   - Check-in: Tomorrow's date
   - Check-out: 2 days later
   - Guests: 2
   - Rooms: 1
4. Submit - Should see success with auto-generated Booking ID
5. Verify booking appears in list with CONFIRMED status

**Test Check-in:**
1. Find the booking you created
2. Click "Check In" button
3. Should see:
   - Booking status → CHECKED_IN
   - Room status → OCCUPIED
   - actual_check_in timestamp recorded

**Test Check-out:**
1. Click "Check Out" button on checked-in booking
2. Should see:
   - Booking status → CHECKED_OUT
   - Room status → AVAILABLE
   - actual_check_out timestamp recorded

**Test Reports:**
1. Go to: `/owner/reports`
2. Select date range (current month)
3. Should see:
   - Revenue report with totals
   - Booking report with counts
   - Occupancy report with percentages
4. Click "Export Revenue CSV" - File should download
5. Click "Export Bookings CSV" - File should download
6. Open CSV files - Verify data is correct

---

### Step 2: Test Manager Access (10 minutes)

**Logout and Login:**
1. Logout
2. Login as Manager (manager@hotel1.com / password123)
3. Should redirect to: `/manager/dashboard`

**Test Dashboard:**
- [ ] Should NOT see revenue chart
- [ ] Should see booking trend chart
- [ ] Should see occupancy trend chart
- [ ] Verify metrics show correctly

**Test Permissions:**
1. Go to: `/manager/reservations`
2. Try to create a booking - Should work ✅
3. Try to edit a booking - Should work ✅
4. Look for "Delete" button - Should NOT exist ❌

5. Go to: `/manager/rooms`
6. Try to create a room - Should work ✅
7. Try to edit a room - Should work ✅
8. Look for "Delete" button - Should NOT exist ❌

9. Go to: `/manager/reports`
10. Should see reports but NO revenue data
11. Try to export revenue - Should fail or button not visible

**Test URL Access (Security Check):**
1. Try to access: `/owner/staff` - Should get 403 Forbidden
2. Try to access: `/owner/reports/export-revenue` - Should get 403 Forbidden

---

### Step 3: Test Receptionist Access (10 minutes)

**Logout and Login:**
1. Logout
2. Login as Receptionist (reception@hotel1.com / password123)
3. Should redirect to: `/reception/dashboard`

**Test Dashboard:**
- [ ] Should see Today's Check-ins list
- [ ] Should see Today's Check-outs list
- [ ] Should see Pending Operations
- [ ] Should see Room Availability

**Test Permissions:**
1. Go to: `/reception/reservations`
2. Try to create a booking - Should work ✅
3. Look for "Edit" button - Should NOT exist ❌
4. Look for "Delete" button - Should NOT exist ❌
5. Can view booking details - Should work ✅
6. Can check-in/check-out - Should work ✅

7. Go to: `/reception/rooms`
8. Can view room list - Should work ✅
9. Look for "Create Room" button - Should NOT exist ❌
10. Look for "Edit" button - Should NOT exist ❌
11. Look for "Delete" button - Should NOT exist ❌

**Test URL Access (Security Check):**
1. Try to access: `/owner/staff` - Should get 403 Forbidden
2. Try to access: `/manager/rooms/create` - Should get 403 Forbidden
3. Try to access: `/owner/reports` - Should get 403 Forbidden

---

## 4. Validation Testing (10 minutes)

### Test Double Booking Prevention:

**Setup:**
1. Login as Owner
2. Create a booking for Room 101 from March 10-15

**Test Overlap:**
1. Try to create another booking for same room:
   - Check-in: March 12
   - Check-out: March 14
2. Should get error: "Room is not available for selected dates"

**Test Valid Booking:**
1. Try booking same room:
   - Check-in: March 16
   - Check-out: March 18
2. Should succeed (no overlap)

---

### Test Room Number Uniqueness:

**Test:**
1. Go to: `/owner/rooms/create`
2. Try to create room with existing room number (e.g., 101)
3. Should get validation error: "Room number already exists"

---

### Test Room Deletion Protection:

**Test:**
1. Find a room with active bookings
2. Try to delete it
3. Should get error: "Cannot delete room with active bookings"

**Test Valid Deletion:**
1. Create a new room (e.g., Room 999)
2. Don't create any bookings for it
3. Delete it - Should succeed

---

## 5. CSV Export Testing (5 minutes)

### Test Revenue Export (Owner Only):
1. Login as Owner
2. Go to: `/owner/reports`
3. Select date range
4. Click "Export Revenue"
5. Open CSV file
6. Verify columns: Booking ID, Guest Name, Room, Check-in, Check-out, Nights, Amount, Payment Method, Status

### Test Booking Export:
1. Click "Export Bookings"
2. Open CSV file
3. Verify columns include: Guest details, Room, Dates, Amount, Status, Created By

### Test Occupancy Export:
1. Click "Export Occupancy"
2. Open CSV file
3. Verify columns: Room Number, Room Type, Status, Bookings, Nights Booked, Revenue

---

## 6. Chart.js Testing (5 minutes)

### Test Owner Dashboard Charts:

**Revenue Chart:**
1. Login as Owner
2. Go to dashboard
3. Look for "6-Month Revenue Trend" chart
4. Should see line chart with 6 data points
5. Hover over points - Should show tooltip with amount

**Booking Chart:**
1. Look for "6-Month Booking Trend" chart
2. Should see bar chart with 6 bars
3. Verify months are labeled correctly

### Test Manager Dashboard Charts:

**Booking Trend:**
1. Login as Manager
2. Should see booking trend chart (no revenue chart)

**Occupancy Trend:**
1. Should see occupancy rate chart
2. Verify percentages make sense

---

## 7. Database Verification (5 minutes)

### Check New Columns Exist:

**MySQL Commands:**
```sql
-- Check bookings table
DESCRIBE bookings;
-- Should see: created_by, actual_check_in, actual_check_out

-- Check rooms table
DESCRIBE rooms;
-- Should see: capacity, is_available

-- Check hotels table
DESCRIBE hotels;
-- Should see: logo, owner_id

-- Check users table
DESCRIBE users;
-- Should see: created_by
```

### Check Data Integrity:
```sql
-- Check booking with creator
SELECT booking_id, guest_name, created_by FROM bookings LIMIT 5;

-- Check room availability
SELECT room_number, status, is_available FROM rooms;

-- Check actual check-in/out times
SELECT booking_id, actual_check_in, actual_check_out FROM bookings WHERE status = 'CHECKED_OUT';
```

---

## 8. Error Handling Testing (5 minutes)

### Test Invalid Inputs:

**Invalid Date Range:**
1. Create booking with check-out before check-in
2. Should get validation error

**Invalid Email:**
1. Create booking with email "notanemail"
2. Should get validation error

**Negative Price:**
1. Create room with price -1000
2. Should get validation error

**Too Large File:**
1. Upload room photo > 2MB
2. Should get validation error

---

## 9. Performance Testing (Optional)

### Create Large Dataset:
```bash
# Use Tinker to create test data
php artisan tinker

# Create 100 bookings
for($i=1; $i<=100; $i++) {
    \App\Models\Booking::create([
        'hotel_id' => 1,
        'room_id' => 1,
        'booking_id' => 'BK'.date('Ymd').$i,
        'guest_name' => 'Guest '.$i,
        'guest_email' => 'guest'.$i.'@test.com',
        'guest_phone' => '1712345'.$i,
        'check_in_date' => now()->addDays($i),
        'check_out_date' => now()->addDays($i+2),
        'num_guests' => 2,
        'num_rooms' => 1,
        'total_price' => 7000,
        'payment_status' => 'PAID',
        'status' => 'CONFIRMED',
    ]);
}
```

**Test Pagination:**
1. Go to reservations list
2. Should see pagination controls
3. Navigate between pages - Should load quickly (<1s)

---

## 10. Security Testing (Critical)

### Test Hotel ID Isolation:

**Setup:**
1. Create 2 hotels with different owners
2. Create bookings for each hotel

**Test:**
1. Login as Hotel 1 Owner
2. Note a booking ID from Hotel 1
3. Logout

4. Login as Hotel 2 Owner
5. Try to access Hotel 1's booking via URL: `/owner/reservations/{hotel1_booking_id}`
6. Should get 404 or 403 error (data not accessible)

### Test Role Escalation:

**Test:**
1. Login as Receptionist
2. Try to access: `/owner/staff/create` via URL
3. Should get 403 Forbidden

4. Try POST request to delete room
5. Should get 403 Forbidden

### Test CSRF Protection:

**Test:**
1. Create a form without @csrf token
2. Try to submit
3. Should get 419 Page Expired error

---

## 11. Common Issues & Solutions

### Issue: Migration fails with foreign key error
**Solution:**
```bash
# Skip problematic migrations
php artisan migrate --path=database/migrations/2026_03_05_000001_add_missing_columns_for_production.php
```

### Issue: Charts not displaying
**Solution:**
1. Check browser console for JS errors
2. Verify Chart.js CDN is loaded in blade template
3. Check if data is passed to view: `dd($revenueData, $revenueLabels)`

### Issue: Double booking not prevented
**Solution:**
1. Check Booking model hasOverlap() method exists
2. Verify overlap validation runs in store() and update() methods
3. Check date format (should be Y-m-d)

### Issue: CSV export downloads empty
**Solution:**
1. Check date range includes bookings
2. Verify hotel_id filter is correct
3. Check booking status (must be PAID for revenue report)

### Issue: Room status not updating
**Solution:**
1. Check DB transaction wraps the update
2. Verify both booking and room updates happen
3. Check status values use UPPERCASE

---

## 12. Success Criteria

### System is production-ready when:
- [x] All migrations run successfully
- [x] No PHP errors in any controller
- [x] All routes return 200 (or appropriate status codes)
- [ ] Owner can perform all CRUD operations
- [ ] Manager has limited access (no delete, no revenue)
- [ ] Receptionist can only view and operate check-in/out
- [ ] Double booking validation works
- [ ] Room deletion protection works
- [ ] CSV exports download correctly
- [ ] Charts display with real data
- [ ] Hotel ID filtering prevents cross-hotel access
- [ ] CSRF protection works
- [ ] All status comparisons use UPPER()
- [ ] Database transactions preserve data integrity

---

## 13. Quick Test Script

**Copy/Paste this in terminal:**
```bash
# Test migrations
php artisan migrate:status | grep "2026_03_05_000001_add_missing_columns_for_production"

# Test routes
php artisan route:list | grep "reservations"
php artisan route:list | grep "rooms"
php artisan route:list | grep "reports"

# Clear caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Start server
php artisan serve
```

---

## 14. Browser Testing (Chrome DevTools)

### Network Tab:
1. Open DevTools (F12)
2. Go to Network tab
3. Create a booking
4. Check request:
   - Method: POST
   - Status: 302 (redirect)
   - Check response for validation errors

### Console Tab:
1. Check for JavaScript errors
2. Chart.js should load without errors
3. No CORS errors

### Application Tab:
1. Check Cookies
2. Verify XSRF-TOKEN exists
3. Verify laravel_session exists

---

## 15. Final Checklist Before Production

- [ ] Run: `php artisan migrate` (production database)
- [ ] Set: `APP_ENV=production` in `.env`
- [ ] Set: `APP_DEBUG=false` in `.env`
- [ ] Run: `php artisan key:generate`
- [ ] Run: `composer dump-autoload --optimize`
- [ ] Run: `php artisan config:cache`
- [ ] Run: `php artisan route:cache`
- [ ] Run: `php artisan view:cache`
- [ ] Test all critical user flows
- [ ] Monitor error logs: `storage/logs/laravel.log`
- [ ] Set up backup system
- [ ] Configure SSL certificate
- [ ] Set up monitoring (optional)

---

**Happy Testing! 🎉**

If you encounter any issues, refer to the PRODUCTION_SYSTEM_SUMMARY.md for detailed documentation.
