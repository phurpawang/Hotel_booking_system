# EMAIL SYSTEM - QUICK REFERENCE

## 🚀 QUICK FIX (3 Steps)

### **Step 1: Get Gmail App Password**

1. Enable 2FA: https://myaccount.google.com/security
2. Create App Password: https://myaccount.google.com/apppasswords
3. Copy the 16-character password (remove spaces)

### **Step 2: Update .env File**

Open `.env` and update these lines:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
```

**Replace:**
- `your_email@gmail.com` → Your actual Gmail address
- `your_app_password_here` → The 16-character app password (no spaces)

### **Step 3: Test It**

```bash
# Clear cache
php artisan config:clear

# Test email
# Visit: http://localhost/BHBS/test-email
```

✅ **Done!** Emails should now work.

---

## 🧪 TESTING

### **Quick Test:**
```
http://localhost/BHBS/test-email
```

**Expected Result:**
```json
{
  "status": "success",
  "message": "Test email sent successfully!"
}
```

### **Test Booking Email:**
1. Go to homepage
2. Search hotels
3. Make a booking
4. Check your email inbox

### **Test Password Reset:**
1. Go to: `http://localhost/BHBS/forgot-password`
2. Enter user email
3. Check email for reset link

---

## 🐛 TROUBLESHOOTING

### **"Connection refused"**
✅ Check internet connection
✅ Verify Gmail credentials
✅ Use App Password (not regular password)

### **"Username and Password not accepted"**
✅ Enable 2FA on Gmail first
✅ Generate new App Password
✅ Remove spaces from password

### **Still not working?**
Check logs:
```bash
Get-Content storage\logs\laravel.log -Tail 50
```

---

## 📧 CURRENT STATUS

### ✅ **What's Fixed:**
- Email driver changed to SMTP
- Password reset logging added
- Test email route created
- Cache cleared
- Configuration verified

### ✅ **What Works:**
- Booking confirmation emails (code ready)
- Password reset emails (code ready)
- Error handling and logging
- Email templates

### ⚠️ **What You Need:**
- Gmail App Password in .env file
- Run: `php artisan config:clear`

---

## 📝 IMPORTANT ROUTES

- Test Email: `/test-email`
- Forgot Password: `/forgot-password`
- Login: `/hotel/login`
- Homepage: `/`

---

## 🔧 USEFUL COMMANDS

```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear

# View logs
Get-Content storage\logs\laravel.log -Tail 50

# Search for email errors
Select-String -Path storage\logs\laravel.log -Pattern "email"
```

---

**Need More Help?** See `EMAIL_SYSTEM_COMPLETE_FIX.md` for detailed guide.
