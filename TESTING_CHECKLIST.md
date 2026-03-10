# 🚀 BHBS QUICK START CHECKLIST

## ✅ Pre-Testing Checklist

Before testing the new system, verify these are in place:

### 1. Database Setup
```bash
- [ ] Database 'bhbs' exists
- [ ] Run migrations: php artisan migrate
- [ ] (Optional) Seed data: php artisan db:seed
```

### 2. Laravel Configuration
```bash
- [ ] .env file configured correctly
- [ ] APP_KEY generated: php artisan key:generate
- [ ] Storage linked: php artisan storage:link
- [ ] Cache cleared: php artisan cache:clear
```

### 3. Web Server
```bash
- [ ] Apache/XAMPP running
- [ ] PHP 8.x enabled
- [ ] MySQL running
- [ ] Site accessible at http://127.0.0.1:8000
```

---

## 🧪 Testing Workflow

### Test 1: Property Registration
```
URL: http://127.0.0.1:8000/hotel/register

Fill in:
- Owner Name: John Doe
- Owner Email: owner@example.com
- Password: password123
- Hotel Name: Test Hotel
- Upload required documents

Expected: ✅ Redirect to registration success page
Expected: ✅ User created with role='OWNER'
```

### Test 2: Admin Approval
```
URL: http://127.0.0.1:8000/admin/login

Login as admin
Go to Hotels Management
Approve "Test Hotel"

Expected: ✅ Hotel status changes to 'approved'
```

### Test 3: Owner Login & Dashboard
```
URL: http://127.0.0.1:8000/hotel/login

Login with:
- Hotel ID: [from registration]
- Email: owner@example.com
- Password: password123

Expected: ✅ Redirect to /owner/dashboard
Expected: ✅ Blue-themed dashboard with charts
Expected: ✅ Navigation sidebar visible
```

### Test 4: Create Manager
```
In Owner Dashboard:
Click "Staff Management" sidebar
Click "Add Staff" button

Fill in:
- Name: Jane Manager
- Email: manager@example.com
- Role: MANAGER
- Password: password123

Expected: ✅ Manager account created
Expected: ✅ Appears in staff list
```

### Test 5: Manager Login
```
Logout from owner account
Login as manager:
- Email: manager@example.com
- Password: password123

Expected: ✅ Redirect to /manager/dashboard
Expected: ✅ Green-themed dashboard
Expected: ✅ NO "Staff Management" link
Expected: ✅ NO revenue data visible
```

### Test 6: Create Receptionist
```
Login as owner again
Go to Staff Management
Create receptionist:
- Name: Bob Reception
- Email: reception@example.com
- Role: RECEPTIONIST
- Password: password123

Expected: ✅ Receptionist account created
```

### Test 7: Receptionist Login
```
Login as receptionist:
- Email: reception@example.com
- Password: password123

Expected: ✅ Redirect to /reception/dashboard
Expected: ✅ Purple-themed dashboard
Expected: ✅ Simplified navigation
Expected: ✅ Check-in/out lists visible
```

### Test 8: Middleware Protection
```
As Manager, try to access:
- http://127.0.0.1:8000/owner/staff

Expected: ✅ HTTP 403 Forbidden

As Receptionist, try to access:
- http://127.0.0.1:8000/manager/dashboard

Expected: ✅ HTTP 403 Forbidden
```

---

## 🐛 Common Issues & Fixes

### Issue: "Route not found" error
**Fix:** Clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: "Class not found" error
**Fix:** Regenerate autoload files:
```bash
composer dump-autoload
```

### Issue: Charts not displaying
**Fix:** Check browser console for JavaScript errors
- Ensure Chart.js CDN is accessible
- Check internet connection

### Issue: 403 Forbidden on dashboard
**Fix:** Check user role in database:
```sql
SELECT id, name, email, role FROM users WHERE email='your@email.com';
```
- Role should be UPPERCASE (OWNER, MANAGER, RECEPTIONIST)

### Issue: Login redirects to wrong page
**Fix:** Check AuthController's redirectBasedOnRole() method
- Should redirect to 'owner.dashboard', 'manager.dashboard', 'reception.dashboard'

### Issue: Staff creation fails
**Fix:** Check:
- Email must be unique
- Password minimum 8 characters
- Role must be MANAGER or RECEPTIONIST

---

## 📊 Test Data Scenarios

### Scenario 1: Peak Season Booking
```
Create test bookings:
- 50 bookings for next month
- Mix of confirmed/pending/cancelled statuses
- Check dashboard KPIs update correctly
```

### Scenario 2: Multiple Staff Members
```
Create:
- 2 Managers
- 3 Receptionists
- Test each login
- Verify role-based access
```

### Scenario 3: Check-in/Check-out Flow
```
As Receptionist:
- Find today's check-in
- Click "Check In" button
- Verify booking status changes
- Test check-out similarly
```

---

## 🔍 Validation Checks

### Database Checks
```sql
-- Verify owner role assignment
SELECT name, email, role FROM users WHERE role='OWNER';

-- Check staff created by owner
SELECT name, email, role, created_by FROM users WHERE role IN ('MANAGER', 'RECEPTIONIST');

-- Verify hotel approval status
SELECT name, status, created_at FROM hotels WHERE status='approved';
```

### File Integrity Checks
```bash
- [ ] resources/views/owner/dashboard.blade.php exists
- [ ] resources/views/manager/dashboard.blade.php exists
- [ ] resources/views/reception/dashboard.blade.php exists
- [ ] resources/views/owner/staff.blade.php exists
- [ ] app/Http/Controllers/OwnerDashboardController.php exists
- [ ] app/Http/Controllers/ManagerDashboardController.php exists
- [ ] app/Http/Controllers/ReceptionistDashboardController.php exists
```

### Route Verification
```bash
php artisan route:list | grep owner
php artisan route:list | grep manager
php artisan route:list | grep reception
```

Expected routes:
- GET /owner/dashboard
- GET /owner/staff
- POST /owner/staff/create
- DELETE /owner/staff/{id}
- GET /manager/dashboard
- GET /reception/dashboard

---

## 📈 Performance Checks

### Load Time Expectations
- Dashboard load: < 2 seconds
- Chart rendering: < 1 second
- Calendar load: < 1.5 seconds

### Database Query Optimization
```php
// Verify eager loading in controllers
$bookings = Booking::with('room')->get(); // ✅ Good
$bookings = Booking::all(); // ❌ N+1 queries
```

---

## 🎯 Success Criteria

System is ready for production when:

- [x] All 8 test scenarios pass
- [x] No unauthorized access possible
- [x] All dashboards load without errors
- [x] Charts display real or placeholder data
- [x] Staff creation works for owner
- [x] Middleware blocks unauthorized routes
- [x] Passwords are hashed in database
- [x] CSRF protection on all forms
- [x] Responsive design on mobile/tablet
- [x] No console errors in browser

---

## 📚 Additional Resources

- **Laravel Docs:** https://laravel.com/docs/10.x
- **Chart.js Docs:** https://www.chartjs.org/docs/latest/
- **FullCalendar Docs:** https://fullcalendar.io/docs
- **Tailwind CSS:** https://tailwindcss.com/docs

---

## 🆘 Emergency Rollback

If system has critical issues:

```bash
# Option 1: Restore from backup
mysql -u root bhbs < backup.sql

# Option 2: Rollback migrations
php artisan migrate:rollback

# Option 3: Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

**Ready to test!** 🚀

Start with Test 1 (Property Registration) and work through all 8 tests sequentially.

Good luck! 🎉
