# EMAIL SYSTEM - COMPLETE FIX GUIDE

## 🔍 ISSUES FOUND AND FIXED

### ✅ **Issue 1: Email Driver Set to 'log'**
**Problem:** `.env` had `MAIL_MAILER=log` which writes emails to log files instead of sending them.

**Fixed:** Changed to `MAIL_MAILER=smtp` to use actual email sending via Gmail SMTP.

### ✅ **Issue 2: Missing Error Logging in Password Reset**
**Problem:** Password reset email errors weren't being logged.

**Fixed:** Added comprehensive logging to `PasswordResetLinkController.php`:
- Success logs with email and reset URL
- Error logs with full exception details

### ✅ **Issue 3: No Test Email Route**
**Problem:** No easy way to test if email configuration is working.

**Fixed:** Added `/test-email` route that:
- Sends a test email
- Shows current mail configuration
- Returns detailed error messages if it fails
- Logs everything to laravel.log

---

## 📧 STEP-BY-STEP EMAIL SETUP

### **STEP 1: Configure Gmail for SMTP**

#### Option A: Using Gmail (Recommended for Production)

1. **Enable 2-Factor Authentication** on your Gmail account:
   - Go to: https://myaccount.google.com/security
   - Enable "2-Step Verification"

2. **Create an App Password**:
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Windows Computer"
   - Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

3. **Update `.env` file** with your Gmail credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bhutanhotels.bt"
MAIL_FROM_NAME="Bhutan Hotel Booking System"
```

**Important Notes:**
- Use the **App Password** (16 characters), NOT your regular Gmail password
- Remove spaces from the app password
- Example: `abcdefghijklmnop`

#### Option B: Using Mailtrap (For Testing Only)

For testing without sending real emails:

1. Sign up at: https://mailtrap.io
2. Get your SMTP credentials from the inbox
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bhutanhotels.bt"
MAIL_FROM_NAME="Bhutan Hotel Booking System"
```

---

### **STEP 2: Clear Configuration Cache**

After updating `.env`, run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**✅ Already Done:** I've cleared all caches for you.

---

### **STEP 3: Test Email Configuration**

#### Test via Browser:

1. **Visit the test route:**
   ```
   http://localhost/BHBS/test-email
   ```

2. **Check the response:**
   - ✅ Success: You'll see JSON with "status": "success"
   - ❌ Error: You'll see detailed error message

#### Test via Command Line:

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });
```

---

### **STEP 4: Verify Database Tables**

All required tables exist and are configured:

✅ **users** - Stores user accounts (Owner, Manager, Receptionist)
   - Column: `email` (for user emails)

✅ **bookings** - Stores guest bookings
   - Column: `guest_email` (for guest emails)
   - Column: `guest_name` (for personalization)
   - Column: `guest_phone` (alternative contact)

✅ **password_reset_tokens** - Stores password reset tokens
   - Column: `email` (user email)
   - Column: `token` (hashed reset token)
   - Column: `created_at` (expiration tracking)

---

## 📬 EMAIL FEATURES STATUS

### ✅ **Booking Confirmation Email** - CONFIGURED

**Location:** `app/Http/Controllers/GuestBookingController.php` (Line 237)

**What it does:**
- Sends email when guest confirms booking
- Includes: Booking ID, Hotel Name, Room Type, Check-in/out dates, Total Price
- Has error handling with logging

**Email Template:** `resources/views/emails/booking-confirmation.blade.php`

**How it works:**
```php
try {
    Mail::to($booking->guest_email)->send(new BookingConfirmation($booking));
    Log::info('Booking confirmation email sent', ['booking_id' => $bookingId]);
} catch (\Exception $e) {
    Log::error('Failed to send booking confirmation', ['error' => $e->getMessage()]);
}
```

---

### ✅ **Password Reset Email** - CONFIGURED

**Location:** `app/Http/Controllers/Auth/PasswordResetLinkController.php` (Line 64)

**What it does:**
- Sends secure reset link when user forgets password
- Token expires in 60 minutes
- One-time use tokens

**Email Template:** `resources/views/emails/password-reset.blade.php`

**How it works:**
```php
try {
    Mail::to($request->email)->send(new PasswordReset($resetUrl));
    \Log::info('Password reset email sent', ['email' => $request->email]);
} catch (\Exception $e) {
    \Log::error('Failed to send password reset', ['error' => $e->getMessage()]);
}
```

---

## 🐛 DEBUGGING EMAILS

### **Check Laravel Logs**

**Location:** `storage/logs/laravel.log`

Look for these entries:
```
[2026-03-08] local.INFO: Booking confirmation email sent successfully
[2026-03-08] local.INFO: Password reset email sent successfully
[2026-03-08] local.ERROR: Failed to send booking confirmation email
```

### **Common Error Messages**

#### Error: "Connection refused on port 587"
**Cause:** Gmail SMTP not reachable or wrong credentials

**Fix:**
1. Check internet connection
2. Verify Gmail credentials in `.env`
3. Make sure you're using App Password, not regular password
4. Check if firewall is blocking port 587

#### Error: "Username and Password not accepted"
**Cause:** Wrong Gmail credentials or not using App Password

**Fix:**
1. Enable 2-Factor Authentication on Gmail
2. Generate new App Password
3. Update `.env` with the new password
4. Run `php artisan config:clear`

#### Error: "Must issue a STARTTLS command first"
**Cause:** Wrong encryption setting

**Fix:**
Update `.env`:
```env
MAIL_ENCRYPTION=tls
```

#### Error: "Connection timeout"
**Cause:** Port 587 might be blocked, or wrong host

**Fix:**
Try alternative port:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

---

## 🧪 TESTING CHECKLIST

### **Test 1: Email Configuration**
- [ ] Visit `/test-email`
- [ ] See "status": "success"
- [ ] No errors in response

### **Test 2: Booking Confirmation Email**
1. [ ] Go to homepage
2. [ ] Search for available hotels
3. [ ] Make a test booking
4. [ ] Check `storage/logs/laravel.log` for:
   ```
   Booking confirmation email sent successfully
   ```
5. [ ] Check email inbox (or Mailtrap if testing)

### **Test 3: Password Reset Email**
1. [ ] Go to `/forgot-password`
2. [ ] Enter a valid user email
3. [ ] Click "Send Reset Link"
4. [ ] Check `storage/logs/laravel.log` for:
   ```
   Password reset email sent successfully
   ```
5. [ ] Check email inbox for reset link
6. [ ] Click reset link and set new password

---

## 📊 MONITORING EMAIL DELIVERY

### **Check Email Sending Status**

```bash
# View recent logs
tail -50 storage/logs/laravel.log

# Search for email-related logs
grep -i "email" storage/logs/laravel.log | tail -20

# Search for errors
grep -i "Failed to send" storage/logs/laravel.log
```

### **PowerShell Commands (Windows)**

```powershell
# View last 50 lines
Get-Content storage\logs\laravel.log -Tail 50

# Search for email logs
Select-String -Path storage\logs\laravel.log -Pattern "email" | Select-Object -Last 20

# Search for errors
Select-String -Path storage\logs\laravel.log -Pattern "Failed to send"
```

---

## 🚀 PRODUCTION DEPLOYMENT

### **Before Going Live:**

1. **✅ Use Real SMTP Credentials**
   - Don't use Mailtrap in production
   - Use Gmail or dedicated email service

2. **✅ Update From Address**
   ```env
   MAIL_FROM_ADDRESS="noreply@yourdomain.com"
   MAIL_FROM_NAME="Your Company Name"
   ```

3. **✅ Remove Test Route**
   - Comment out or delete the `/test-email` route in `routes/web.php`
   - It's marked for removal in production

4. **✅ Monitor Logs**
   - Set up log monitoring
   - Create alerts for email failures

5. **✅ Test All Scenarios**
   - New booking confirmation
   - Password reset for all user roles (Owner, Manager, Receptionist)
   - Check spam folders

---

## 📝 FILES MODIFIED

### **Modified Files:**

1. **`.env`**
   - Changed `MAIL_MAILER` from `log` to `smtp`
   - Updated SMTP settings for Gmail
   - Set proper FROM address and name

2. **`app/Http/Controllers/Auth/PasswordResetLinkController.php`**
   - Added comprehensive error logging
   - Added success logging with email and URL
   - Improved error messages

3. **`routes/web.php`**
   - Added `/test-email` route for testing

### **Already Configured (No Changes Needed):**

1. ✅ `app/Http/Controllers/GuestBookingController.php` - Has proper email sending with error handling
2. ✅ `app/Mail/BookingConfirmation.php` - Mailable class configured
3. ✅ `app/Mail/PasswordReset.php` - Mailable class configured
4. ✅ `resources/views/emails/booking-confirmation.blade.php` - Email template exists
5. ✅ `resources/views/emails/password-reset.blade.php` - Email template exists

---

## ⚡ QUICK START

### **For Development/Testing:**

1. Update `.env` with Gmail credentials:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_ENCRYPTION=tls
   ```

2. Clear cache:
   ```bash
   php artisan config:clear
   ```

3. Test email:
   ```
   http://localhost/BHBS/test-email
   ```

4. If successful, test booking and password reset flows!

---

## 🎉 SUMMARY

### What Was Fixed:
✅ Changed email driver from 'log' to 'smtp'
✅ Added proper error logging to password reset
✅ Created test email route
✅ Cleared all Laravel caches
✅ Verified database structure
✅ Confirmed email templates exist

### What's Already Working:
✅ Booking confirmation email code
✅ Password reset email code
✅ Error handling and logging
✅ Email templates (HTML)
✅ Database structure

### What You Need to Do:
1. **Update `.env`** with your Gmail credentials (App Password)
2. **Run** `php artisan config:clear`
3. **Test** by visiting `/test-email`
4. **Verify** by making a test booking or password reset

---

**Status:** ✅ All Code Fixed - Ready for Email Configuration
**Date:** March 8, 2026
**Action Required:** Configure Gmail SMTP in `.env` file
