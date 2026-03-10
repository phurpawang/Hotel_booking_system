# 🇧🇹 BHBS Quick Reference Card

## 🔐 DEFAULT CREDENTIALS

### Admin Login
- **URL:** `/admin/login`
- **Username:** `admin`
- **Password:** `password` ⚠️ CHANGE IN PRODUCTION

## 📋 KEY URLS

| Purpose | URL |
|---------|-----|
| Hotel Registration | `/hotel/register` |
| Hotel Login | `/hotel/login` |
| Check Status | `/hotel/check-status` |
| Admin Login | `/admin/login` |
| Owner Dashboard | `/owner/dashboard` |
| Manager Dashboard | `/manager/dashboard` |
| Receptionist Dashboard | `/receptionist/dashboard` |

## 👥 USER ROLES

| Role | Can Do | Cannot Do |
|------|--------|-----------|
| **OWNER** | Register hotel, Create staff, Manage rooms, View reports | Create other owners |
| **MANAGER** | Manage rooms, Update pricing, View bookings | Register hotel, Create staff, Access reports |
| **RECEPTIONIST** | Check-in/out guests, View bookings | Change prices, Manage rooms, Access reports |
| **ADMIN** | Approve/Reject hotels, View all data | Login to hotel dashboard |

## 🏨 HOTEL STATUS FLOW

```
Registration → PENDING → Admin Review → APPROVED/REJECTED
                          ↓
                     Can Login ✓
```

## 🔑 LOGIN REQUIREMENTS

### Hotel Staff Login Needs:
1. Hotel ID (e.g., HTL001)
2. Email
3. Password
4. Hotel Status = APPROVED ✓

## 📱 QUICK COMMANDS

### Setup
```bash
php artisan migrate          # Run migrations
php artisan storage:link     # Link storage
php artisan cache:clear      # Clear cache
```

### Create Admin
```sql
INSERT INTO admins (username, password, created_at, updated_at) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());
```

### Start Server
```bash
php artisan serve
# Access at: http://localhost:8000
```

## 📊 DATABASE QUICK VIEW

### Hotels Table
- `hotel_id` - HTL001, HTL002...
- `status` - pending, approved, rejected
- `email` - unique
- `license_document` - file path
- `ownership_document` - file path

### Users Table
- `hotel_id` - foreign key
- `role` - owner, manager, receptionist
- `password` - bcrypt hashed
- `created_by` - who created this user

## 🚨 TROUBLESHOOTING

| Issue | Solution |
|-------|----------|
| Can't login after approval | Check status is exactly "approved" (lowercase) |
| File upload fails | Run `php artisan storage:link` |
| 404 on routes | Copy `new-web-routes.php` to `web.php` |
| Class not found | Run `composer dump-autoload` |

## ✅ TESTING WORKFLOW

1. **Register Hotel** → Get HTL001
2. **Login as Admin** → Approve hotel
3. **Login as Owner** → Access dashboard
4. **Create Manager** → Add staff
5. **Login as Manager** → Test access
6. **Create Receptionist** → Add staff
7. **Login as Receptionist** → Test check-in

## 📁 FILE UPLOAD RULES

- **Formats:** PDF, JPG, JPEG, PNG
- **Max Size:** 2MB
- **Required:** License + Ownership documents
- **Storage:** `storage/app/public/hotel_documents/`

## 🎨 STATUS BADGES

- 🟡 **PENDING** - Awaiting admin review
- 🟢 **APPROVED** - Can login and operate
- 🔴 **REJECTED** - Cannot login, reason provided

## 🔒 SECURITY FEATURES

✓ Password hashing (bcrypt)  
✓ CSRF protection  
✓ File validation  
✓ Role-based access  
✓ Hotel approval requirement  
✓ Session management  

## 📞 QUICK SUPPORT

**Check Logs:**
```bash
tail -f storage/logs/laravel.log
```

**Debug Database:**
```bash
php artisan tinker
>>> App\Models\Hotel::all()
>>> App\Models\User::where('role', 'owner')->get()
```

## 🎯 SUCCESS INDICATORS

- [ ] Admin can login
- [ ] Hotel can register
- [ ] Admin can approve
- [ ] Owner can login after approval
- [ ] Owner can create staff
- [ ] Staff can login with hotel ID
- [ ] All dashboards load correctly

---

**Print this card and keep it handy during implementation!**

**System:** Bhutan Hotel Booking System v2.0  
**Date:** March 4, 2026
