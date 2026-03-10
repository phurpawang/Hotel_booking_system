# Password Reset - Quick Reference

## 🔗 Important URLs

### For Users:
- **Forgot Password:** `/forgot-password` or `http://yourdomain.com/forgot-password`
- **Login Page:** `/hotel/login` or `http://yourdomain.com/hotel/login`

### Admin/Testing:
- **Route List:** Run `php artisan route:list | grep password`
- **Check Email Config:** Check `.env` file

---

## ⚡ Quick Commands

```bash
# Clear all cache (if routes not working)
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# View all routes
php artisan route:list

# Test email sending
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# Check logs for errors
tail -f storage/logs/laravel.log
```

---

## 📧 Email Configuration (.env)

**For Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bhutanhotels.bt
MAIL_FROM_NAME="Bhutan Hotel Booking System"
```

**For Mailtrap (Testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bhutanhotels.bt
MAIL_FROM_NAME="Bhutan Hotel Booking System"
```

---

## 🎯 User Flow (3 Simple Steps)

**Step 1:** Forgot Password
- User clicks "Forgot Password?" on login
- Enters email → Clicks "Send Reset Link"

**Step 2:** Check Email
- User receives email with reset link
- Link valid for 60 minutes

**Step 3:** Reset Password
- User clicks link in email
- Enters new password (8+ chars)
- Confirms password → Clicks "Reset Password"
- Redirected to login → Can login with new password

---

## ✅ Testing Checklist

**Basic Testing:**
- [ ] Visit `/forgot-password` → See form
- [ ] Enter email → Click send → See success message
- [ ] Check email inbox → Receive password reset email
- [ ] Click link in email → Opens reset page
- [ ] Enter new password → Submit → Redirected to login
- [ ] Login with new password → Works!

**Edge Cases:**
- [ ] Wrong email → Shows error
- [ ] Expired link (after 60 min) → Shows error
- [ ] Reuse link → Shows error
- [ ] Password less than 8 chars → Shows error
- [ ] Mismatched passwords → Shows error

---

## 🔧 Common Issues & Fixes

### "Route [password.request] not defined"
```bash
php artisan route:clear
php artisan cache:clear
```

### Email Not Sending
1. Check `.env` file has correct MAIL_ settings
2. Run `php artisan config:clear`
3. Check `storage/logs/laravel.log` for errors
4. Test with Mailtrap first

### "Invalid or expired reset token"
- Link expired (>60 minutes) → Request new link
- Link already used → Request new link
- Token doesn't match → Request new link

### Password Not Updating
```bash
php artisan cache:clear
# Check database: SELECT * FROM password_reset_tokens;
# Check database: SELECT email, password FROM users WHERE email='test@example.com';
```

---

## 📝 Key Features

✅ **Secure:** Tokens hashed, 60-min expiry, one-time use  
✅ **User-Friendly:** Clean Bootstrap 5 UI, clear messages  
✅ **Role Support:** Works for Owner, Manager, Receptionist  
✅ **Professional Emails:** HTML template with branding  
✅ **Validation:** Email format, password length, CSRF protection  

---

## 🎨 Customization Options

**Change Token Expiry:**
File: `config/auth.php`
```php
'passwords' => [
    'users' => [
        'expire' => 90, // Change from 60 to 90 minutes
    ],
],
```

**Change Password Minimum Length:**
File: `app/Http/Controllers/Auth/NewPasswordController.php`
```php
'password' => ['required', 'confirmed', 'min:10'], // Change from 8 to 10
```

**Change Email Template:**
File: `resources/views/emails/password-reset.blade.php`
- Customize colors, text, design

**Change Success Message:**
File: `app/Http/Controllers/Auth/NewPasswordController.php`
```php
return redirect()
    ->route('hotel.login')
    ->with('success', 'Your custom message here!');
```

---

## 📊 Database Reference

**Table:** `password_reset_tokens`

```sql
-- View all reset tokens
SELECT * FROM password_reset_tokens;

-- Delete old tokens (manual cleanup)
DELETE FROM password_reset_tokens 
WHERE created_at < NOW() - INTERVAL 1 HOUR;

-- Check specific user's token
SELECT * FROM password_reset_tokens WHERE email = 'user@example.com';
```

---

## 🚀 Production Deployment

**Before Going Live:**
1. ✅ Test complete flow on local/staging
2. ✅ Configure production email settings in `.env`
3. ✅ Test email delivery in production
4. ✅ Clear all caches: `php artisan optimize`
5. ✅ Verify HTTPS is enabled
6. ✅ Test with real user accounts

**After Deployment:**
1. Test password reset with test account
2. Monitor `storage/logs/laravel.log` for errors
3. Check email delivery rates
4. Get user feedback

---

## 📞 Quick Help

**Files to Check if Something's Wrong:**
1. `routes/web.php` → Routes configured?
2. `.env` → Email settings correct?
3. `storage/logs/laravel.log` → Any errors?
4. `config/mail.php` → Mail config correct?

**Useful Artisan Commands:**
```bash
php artisan route:list        # List all routes
php artisan config:show mail  # Show mail configuration
php artisan queue:work        # If using queues for email
php artisan migrate:status    # Check migrations
```

---

**Need More Help?** Check `PASSWORD_RESET_SYSTEM.md` for detailed documentation.
