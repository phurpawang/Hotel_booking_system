# Bhutan Hotel Booking System - Restructured Authentication

## Overview
This is a complete restructure of the authentication and registration system for the Bhutan National Hotel Booking System using Laravel 10, PHP, MySQL, HTML, and Bootstrap 5.

## Key Changes

### ☑️ Removed PIN System
- **REMOVED**: PIN-based authentication
- **IMPLEMENTED**: Password-based authentication using bcrypt

### ☑️ Role-Based Registration
- **OWNER**: Can register a hotel
- **MANAGER**: CANNOT register a hotel (created by owner)
- **RECEPTIONIST**: CANNOT register a hotel (created by owner)

### ☑️ Admin Approval System
- Hotels must be approved by admin before login works
- Status: `pending`, `approved`, `rejected`

---

## Installation Steps

### 1. Database Migration

Run the new migrations to restructure the database:

```bash
php artisan migrate:fresh
```

Or if you want to keep existing data, manually run:

```bash
php artisan migrate
```

**Important**: The new migrations will drop and recreate the hotels and users tables with the new structure.

### 2. Create Admin Account

Create an admin account manually in the database:

```sql
INSERT INTO admins (username, password, created_at, updated_at) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());
```

**Default admin credentials:**
- Username: `admin`
- Password: `password`

**⚠️ Change this password in production!**

### 3. Create Storage Link

Ensure file uploads work:

```bash
php artisan storage:link
```

### 4. Set File Permissions

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 5. Update Routes

Replace your `routes/web.php` content with `routes/new-web-routes.php`:

```bash
# Backup existing routes
cp routes/web.php routes/web-backup.php

# Use new routes
cp routes/new-web-routes.php routes/web.php
```

---

## Database Structure

### Hotels Table
```
- id (primary key)
- hotel_id (unique, e.g., HTL001)
- hotel_name
- email (unique)
- license_document (file path)
- ownership_document (file path)
- status (enum: pending, approved, rejected)
- rejection_reason (nullable)
- timestamps
```

### Users Table
```
- id (primary key)
- hotel_id (foreign key → hotels.hotel_id)
- name
- email (unique)
- password (bcrypt hashed)
- role (enum: owner, manager, receptionist)
- created_by (foreign key → users.id)
- timestamps
```

---

## User Flows

### 1. Hotel Owner Registration

**URL**: `/hotel/register`

**Process**:
1. Owner fills registration form:
   - Hotel Name
   - Owner Name
   - Owner Email
   - Password (min 8 chars)
   - License Document (PDF/Image)
   - Ownership Document (PDF/Image)
2. System generates unique Hotel ID (HTL001, HTL002, etc.)
3. Hotel status set to `pending`
4. Owner account created with role = `owner`
5. Redirect to success page showing Hotel ID
6. **CANNOT login until admin approves**

### 2. Admin Approval

**URL**: `/admin/login`

**Credentials**: admin / password (default)

**Process**:
1. Admin logs in
2. Views pending hotel registrations
3. Reviews documents
4. **Approves** or **Rejects** with reason
5. If approved, hotel status = `approved`
6. Owner can now login

### 3. Hotel Staff Login

**URL**: `/hotel/login`

**Requirements**:
- Hotel ID (e.g., HTL001)
- Email
- Password

**Process**:
1. System checks if hotel exists
2. Validates hotel is approved
3. Authenticates user credentials
4. Redirects based on role:
   - **Owner** → `/owner/dashboard`
   - **Manager** → `/manager/dashboard`
   - **Receptionist** → `/receptionist/dashboard`

### 4. Owner Creates Staff

**URL**: `/owner/staff`

**Process**:
1. Owner navigates to Staff Management
2. Clicks "Add Staff Member"
3. Fills form:
   - Name
   - Email
   - Role (Manager or Receptionist)
   - Password
4. Staff account created
5. Staff can login using Hotel ID

---

## Role-Based Permissions

### Owner Permissions
✅ Manage hotel profile
✅ Add/Edit rooms
✅ Create Manager accounts
✅ Create Receptionist accounts
✅ View all bookings
✅ View financial reports
✅ Delete staff members

❌ Cannot register multiple hotels
❌ Cannot approve/reject hotels

### Manager Permissions
✅ Manage rooms
✅ Update room pricing
✅ Update room availability
✅ View bookings
✅ Update booking status

❌ Cannot register hotel
❌ Cannot create staff
❌ Cannot delete rooms
❌ Cannot access financial reports

### Receptionist Permissions
✅ View bookings
✅ Check-in guests
✅ Check-out guests
✅ View arrivals/departures

❌ Cannot register hotel
❌ Cannot change prices
❌ Cannot create staff
❌ Cannot access reports
❌ Cannot manage rooms

### Admin Permissions
✅ View all hotels
✅ Approve/Reject hotels
✅ View all users
✅ View all bookings
✅ System-wide reports

---

## Middleware Protection

All routes are protected with role-specific middleware:

- `owner` - Checks if user is owner AND hotel is approved
- `manager` - Checks if user is manager AND hotel is approved
- `receptionist` - Checks if user is receptionist AND hotel is approved
- `admin` - Checks if user is admin

---

## Security Features

✅ **Password Hashing**: bcrypt
✅ **CSRF Protection**: Laravel default
✅ **File Upload Validation**: PDF, JPG, PNG only (Max 2MB)
✅ **Email Uniqueness**: Prevents duplicate registrations
✅ **SQL Injection Protection**: Eloquent ORM
✅ **XSS Protection**: Blade templating
✅ **Session Management**: Secure session handling
✅ **Middleware Authentication**: Route-level protection

---

## Views Created

### Authentication
- `resources/views/auth/hotel-register.blade.php` - Hotel registration form
- `resources/views/auth/hotel-login.blade.php` - Login form
- `resources/views/auth/registration-success.blade.php` - Success page
- `resources/views/auth/check-status.blade.php` - Status checker

### Layouts
- `resources/views/layouts/dashboard.blade.php` - Main dashboard layout

### Owner
- `resources/views/owner/dashboard.blade.php` - Owner dashboard
- `resources/views/owner/staff.blade.php` - Staff management

### Admin
- `resources/views/admin/hotels/pending.blade.php` - Pending approvals

---

## Controllers Created

- `AuthController.php` - Login/Logout
- `NewHotelRegistrationController.php` - Hotel registration
- `AdminDashboardController.php` - Admin operations
- `OwnerDashboardController.php` - Owner operations
- `ManagerDashboardController.php` - Manager operations
- `ReceptionistDashboardController.php` - Receptionist operations

---

## Middleware Created

- `OwnerMiddleware.php` - Owner access control
- `ManagerMiddleware.php` - Manager access control
- `ReceptionistMiddleware.php` - Receptionist access control

---

## Testing the System

### 1. Test Hotel Registration
```
1. Visit: http://localhost/hotel/register
2. Fill all fields
3. Upload documents
4. Submit
5. Note the Hotel ID
```

### 2. Test Admin Approval
```
1. Visit: http://localhost/admin/login
2. Login: admin / password
3. Go to Pending Hotels
4. Approve the hotel
```

### 3. Test Hotel Login
```
1. Visit: http://localhost/hotel/login
2. Enter Hotel ID, Email, Password
3. Should redirect to Owner Dashboard
```

### 4. Test Staff Creation
```
1. Login as Owner
2. Go to Manage Staff
3. Add Manager
4. Logout
5. Login as Manager with Hotel ID
```

---

## Production Checklist

Before deploying to production:

- [ ] Change admin default password
- [ ] Set up proper `.env` file
- [ ] Configure mail settings for notifications
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper file storage (AWS S3, etc.)
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure database backups
- [ ] Set up logging and monitoring
- [ ] Test all user flows
- [ ] Perform security audit

---

## Support & Issues

For issues or questions:
1. Check error logs: `storage/logs/laravel.log`
2. Verify database migrations ran successfully
3. Check file permissions
4. Clear cache: `php artisan cache:clear`

---

## Credits

**System**: Bhutan National Hotel Booking System  
**Framework**: Laravel 10  
**Authentication**: Custom Role-Based System  
**UI**: Bootstrap 5  
**Icons**: Bootstrap Icons  

---

## License

This software is proprietary to Bhutan National Tourism Board.
