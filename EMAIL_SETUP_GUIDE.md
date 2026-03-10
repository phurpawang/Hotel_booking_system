# Email Configuration Setup Guide

## Overview
This guide will help you configure Gmail SMTP for sending booking confirmation emails in your Bhutan Hotel Booking System.

## Prerequisites
1. A Gmail account
2. Gmail App Password (required for security)

## Step 1: Generate Gmail App Password

Since Gmail requires 2-factor authentication for SMTP access, you need to generate an App Password:

1. Go to your Google Account: https://myaccount.google.com/
2. Click on "Security" in the left sidebar
3. Enable "2-Step Verification" if not already enabled
4. After enabling 2FA, go back to Security settings
5. Under "Signing in to Google", click on "App passwords"
6. Select "Mail" as the app and "Other (Custom name)" as the device
7. Enter "BHBS Hotel System" as the name
8. Click "Generate"
9. Copy the 16-character password (save it securely)

## Step 2: Update .env File

Open your `.env` file in the root directory of your project and update the following settings:

```env
APP_NAME="Bhutan Hotel Booking System"

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Important Notes:
- Replace `your-email@gmail.com` with your actual Gmail address
- Replace `your-16-char-app-password` with the App Password generated in Step 1
- **Do not use your regular Gmail password** - use the App Password
- Keep MAIL_PORT as 587 and MAIL_ENCRYPTION as tls

## Step 3: Clear Laravel Cache

After updating the .env file, run these commands in your terminal:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Step 4: Test Email Sending

1. Go to your website's homepage
2. Search for a hotel and make a test booking
3. Fill in all booking details with a real email address
4. Submit the booking
5. Check if you receive a confirmation email

## Email Features Implemented

### Booking Confirmation Email
- **Sent when:** A guest completes a booking
- **Recipient:** Guest's email address
- **Content includes:**
  - Booking ID
  - Hotel name and details
  - Room type
  - Check-in and check-out dates
  - Total price
  - Payment method
  - Special requests (if any)

### Error Handling
- If email fails to send, the system will:
  - Log the error in `storage/logs/laravel.log`
  - Show a warning message to the user
  - Still confirm the booking (email failure won't block booking)

## Troubleshooting

### Email not sending?

1. **Check credentials:**
   ```bash
   php artisan config:show mail
   ```
   Verify your MAIL_USERNAME and check if MAIL_FROM_ADDRESS is set

2. **Check logs:**
   - View Laravel logs: `storage/logs/laravel.log`
   - Look for email-related errors

3. **Common issues:**
   - **"Invalid credentials"**: You're using your regular password instead of App Password
   - **"Connection timeout"**: Check if port 587 is open or try port 465 with `ssl` encryption
   - **"Authentication failed"**: Generate a new App Password

4. **Test configuration:**
   Run this Artisan tinker command:
   ```bash
   php artisan tinker
   ```
   Then:
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('test@example.com')->subject('Test');
   });
   ```

### Alternative SMTP Ports

If port 587 doesn't work, try port 465 with SSL:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## Security Best Practices

1. **Never commit .env file to Git**
   - The .env file should be in .gitignore
   - Always use .env.example as a template

2. **Protect App Passwords**
   - Store them securely
   - Regenerate if compromised

3. **Use different email for production**
   - Consider using a dedicated business email
   - Gmail has daily sending limits (500 emails/day for free accounts)

## Production Recommendations

For production systems, consider using professional email services:
- **SendGrid** - 100 emails/day free
- **Mailgun** - 5,000 emails/month free
- **Amazon SES** - 62,000 emails/month free
- **Mailtrap** (for testing only)

These services offer better deliverability, higher limits, and detailed analytics.

## Files Modified/Created

1. **app/Mail/BookingConfirmation.php** - Mailable class for booking emails
2. **resources/views/emails/booking-confirmation.blade.php** - Email template
3. **app/Http/Controllers/GuestBookingController.php** - Added email sending logic
4. **resources/views/guest/booking-confirmation.blade.php** - Updated to show email status

## Support

If you encounter any issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify .env configuration
3. Test Gmail credentials outside Laravel
4. Check firewall/antivirus settings

---

**Last Updated:** March 6, 2026
**Laravel Version:** 10.x
