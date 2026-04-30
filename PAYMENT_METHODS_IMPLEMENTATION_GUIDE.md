# Bhutan Hotel Booking System - Payment Methods & Commission Logic Update

## Overview
This comprehensive update implements a dual payment method system that correctly handles both online and offline payments with accurate commission tracking.

## What Changed

### 1. **Database Changes** ✅
**Migration File:** `database/migrations/2026_04_27_000000_update_hotel_payouts_for_payment_methods.php`

New fields added to `hotel_payouts` table:
- `online_payment_amount` - Total from online payments
- `offline_payment_amount` - Total from offline payments  
- `online_commission_amount` - Commission deducted from online payments
- `offline_commission_due` - Commission due from hotel for offline payments
- `online_payout_amount` - Net payout to hotel (online - commission)
- `online_payout_status` - Tracks online payout status (pending/paid/cancelled)
- `offline_commission_status` - Tracks offline commission status (pending/received/cancelled)
- `offline_commission_received_at` - When hotel paid the commission
- `offline_commission_reference` - Reference for offline commission payment

### 2. **Model Updates** ✅

#### BookingCommission Model
**File:** `app/Models/BookingCommission.php`

Added scopes:
- `scopeOfflinePayments()` - Filter offline (pay_at_hotel) payments
- `scopeOnlinePayments()` - Filter online (pay_online) payments

Added helper methods:
- `getPaymentMethodDisplayAttribute()` - Returns formatted payment method name
- `getCommissionStatusBadgeAttribute()` - Returns badge info for UI
- `isOnlinePayment()` - Check if online payment
- `isOfflinePayment()` - Check if offline payment

#### HotelPayout Model
**File:** `app/Models/HotelPayout.php`

Updated fillable array with all new fields.

Added methods:
- `markOnlinePayoutAsPaid()` - Mark online payout as paid
- `markOfflineCommissionAsReceived()` - Mark offline commission as received
- `getOnlinePayoutStatusBadgeAttribute()` - Badge for online payout status
- `getOfflineCommissionStatusBadgeAttribute()` - Badge for offline commission status
- `hasOnlinePayments()` - Check if has online payments
- `hasOfflinePayments()` - Check if has offline payments
- `getPaymentMethodBreakdown()` - Get detailed breakdown by payment method

### 3. **Service Updates** ✅
**File:** `app/Services/CommissionService.php`

**Updated Method:** `generateMonthlyPayout()`

**New Logic:**
```
ONLINE PAYMENTS (Platform Collects):
  Guest Payment = Final Amount
  Commission Amount = Guest Payment × Commission Rate (e.g., 10%)
  Hotel Payout = Guest Payment - Commission Amount
  Status = "Pending" until admin marks as paid

OFFLINE PAYMENTS (Hotel Collects):
  Hotel Receives = Full Guest Payment
  Commission Due = Guest Payment × Commission Rate
  Hotel Status = "Commission Pending from Hotel"
```

**Correctly Calculates:**
- Online payments with commission deducted
- Offline payments with commission tracked as due
- Separate totals for each payment method
- Commission only counted once (from what platform collects)

### 4. **Controller Updates** ✅
**File:** `app/Http/Controllers/Admin/CommissionController.php`

Added new methods:
- `offlineCommissionForm()` - Show form to mark offline commission as received
- `markOfflineCommissionAsReceived()` - Process offline commission receipt

### 5. **View Updates** ✅

#### Commission Details View
**File:** `resources/views/admin/commissions/show.blade.php`

**New Features:**
- **Payment Method Breakdown Section** - Shows separate cards for online vs offline
- **Online Payment Card:**
  - Guest Payment (Full Amount)
  - Commission Deducted
  - Net Payout to Hotel
  - Payout Status badge
- **Offline Payment Card:**
  - Amount Collected by Hotel
  - Commission Due from Hotel
  - Commission Status badge
- **Enhanced Table:**
  - Payment Method column with color-coded badges
  - Shows which bookings are online vs offline
- **Action Buttons:**
  - "Mark Online Payout as Paid" (only for online payments with pending status)
  - "Mark Offline Commission as Received" (only for offline payments with pending status)

#### Offline Commission Form
**File:** `resources/views/admin/commissions/offline-commission-form.blade.php` (New)

**Features:**
- Summary card showing:
  - Amount collected by hotel
  - Commission due (10%)
  - Period
- Form to record:
  - Reference number (check #, bank transfer ref, etc.)
  - Optional notes (payment method details)
- Clear UI with guidance

### 6. **Routes** ✅
**File:** `routes/web.php`

Added routes:
```php
Route::get('/commissions/{payout}/offline-commission', [...])
    ->name('commissions.offline-commission-form');
Route::post('/commissions/{payout}/offline-commission', [...])
    ->name('commissions.mark-offline-commission');
```

## Next Steps to Implement

### Step 1: Run Database Migration
```bash
php artisan migrate
```

**What this does:**
- Adds 9 new columns to `hotel_payouts` table
- Maintains backward compatibility

### Step 2: Regenerate Payouts
After migration, regenerate all payouts so old records have the new fields populated:

```bash
# In admin panel, go to:
# Admin > Commissions > Generate Payouts

# Or via CLI:
php artisan tinker
> $commission = app('App\Services\CommissionService');
> $commission->generateAllHotelPayouts(2026, 4);
```

### Step 3: Test the System

**Test Online Payment Flow:**
1. Create a booking with "Online Payment" method
2. Generate commissions for that month
3. Go to admin commissions details
4. Should show:
   - Online Payment section with commission deducted
   - Correct payout amount (payment - commission)
   - "Mark as Paid" button should appear
5. Click "Mark as Paid" and verify status changes

**Test Offline Payment Flow:**
1. Create a booking with "Pay at Hotel" payment method
2. Generate commissions for that month
3. Go to admin commissions details
4. Should show:
   - Offline Payment section
   - Commission due from hotel (not deducted from payout)
   - "Mark Commission as Received" button
5. Click button, enter reference, verify status changes

### Step 4: Update Booking Creation
Ensure when creating bookings, the payment method (pay_online vs pay_at_hotel) is correctly set:

```php
// In booking creation:
$booking->payment_method = $request->payment_method; // 'ONLINE', 'CASH', 'CARD', etc.
$booking->payment_method_type = $paymentMethodType; // 'pay_online' or 'pay_at_hotel'
```

### Step 5: Dashboard Updates (Optional)
Update admin dashboard to show:
- Online payouts pending
- Offline commissions pending  
- Separate earnings from online vs offline
- Commission collection rate

### Step 6: Reports Generation (Optional)
Add filters to commission reports:
```php
// Filter by payment method
$onlinePayments = HotelPayout::with('hotel')
    ->where('online_payment_amount', '>', 0)
    ->get();

$offlinePayments = HotelPayout::with('hotel')
    ->where('offline_payment_amount', '>', 0)
    ->get();
```

## Key Improvements

✅ **Correct Accounting:**
- Commission always deducted only once
- Online vs offline payments tracked separately
- No confusion about who holds the money

✅ **Clear UI:**
- Color-coded payment methods (Blue for online, Pink for offline)
- Separate sections for each payment type
- Clear status labels

✅ **Auditing:**
- Reference numbers for all transactions
- Timestamps for when payments were received
- Notes field for additional context

✅ **Flexible Processing:**
- Admin can mark online payouts and offline commissions independently
- Hotel commission can be received separately from online payout
- Full audit trail maintained

## Database Field Reference

| Field | Type | Purpose |
|-------|------|---------|
| `online_payment_amount` | decimal | Sum of all online guest payments |
| `offline_payment_amount` | decimal | Sum of all offline guest payments |
| `online_commission_amount` | decimal | Commission from online payments |
| `offline_commission_due` | decimal | Commission owed by hotel for offline |
| `online_payout_amount` | decimal | Online payment - online commission |
| `online_payout_status` | enum | pending/paid/cancelled |
| `offline_commission_status` | enum | pending/received/cancelled |
| `offline_commission_received_at` | timestamp | When hotel paid commission |
| `offline_commission_reference` | string | Reference for offline payment |

## Example Scenarios

### Scenario 1: Pure Online Booking
```
Guest Payment: Nu. 10,000
Commission Rate: 10%
Commission Deducted: Nu. 1,000
Hotel Payout: Nu. 9,000
Status: Pending → Mark as Paid
```

### Scenario 2: Pure Offline Booking  
```
Guest Payment (collected by hotel): Nu. 10,000
Commission Rate: 10%
Commission Due from Hotel: Nu. 1,000
Hotel Receives: Nu. 10,000 (directly from guest)
Hotel Owes: Nu. 1,000 (commission)
Status: Commission Pending → Mark as Received
```

### Scenario 3: Mixed Month
```
Online Payments: Nu. 30,000
  Commission: Nu. 3,000
  Net Payout: Nu. 27,000

Offline Payments: Nu. 20,000
  Commission Due: Nu. 2,000

Total Collected: Nu. 50,000
Platform Earned: Nu. 3,000 (from online only)
Hotel Receives: Nu. 27,000 (online) + Nu. 20,000 (offline) = Nu. 47,000
Hotel Owes: Nu. 2,000 (commission)
```

## Support & Troubleshooting

**Issue:** Old payouts showing Nu. 0.00 for new fields
- **Solution:** Regenerate payouts after migration

**Issue:** Commission not showing in online payments
- **Solution:** Check that booking has `payment_method_type = 'pay_online'`

**Issue:** Can't find offline commission form
- **Solution:** Ensure offline payments exist (offline_payment_amount > 0)

**Issue:** Routes not found
- **Solution:** Clear route cache: `php artisan route:clear`

## Files Modified Summary

✅ **Created:**
- Migration: `database/migrations/2026_04_27_000000_update_hotel_payouts_for_payment_methods.php`
- View: `resources/views/admin/commissions/offline-commission-form.blade.php`

✅ **Modified:**
- Model: `app/Models/BookingCommission.php` (added scopes & methods)
- Model: `app/Models/HotelPayout.php` (updated fillable & added methods)
- Service: `app/Services/CommissionService.php` (updated payout generation)
- Controller: `app/Http/Controllers/Admin/CommissionController.php` (added methods)
- View: `resources/views/admin/commissions/show.blade.php` (enhanced UI)
- Routes: `routes/web.php` (added routes)

## Completed Implementation Summary

This update successfully implements:
1. ✅ Dual payment method handling
2. ✅ Correct commission calculation per method
3. ✅ Separate status tracking
4. ✅ Clear UI with payment method badges
5. ✅ Independent payout/commission marking
6. ✅ Full audit trail with references
7. ✅ Database schema to support new logic
8. ✅ Model methods for easy queries
9. ✅ Controller actions for admin operations
10. ✅ Routes for new functionality

The system is now ready for deployment after running the migration!
