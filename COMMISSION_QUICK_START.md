# Commission System - Quick Start

Get the commission-based booking system up and running in 5 minutes!

## 🚀 Quick Setup (5 Steps)

### Step 1: Run Migrations (1 min)

```bash
php artisan migrate
```

✅ This adds commission tables and fields to your database.

---

### Step 2: Backfill Existing Rooms (1 min)

```bash
# See what will happen (safe)
php artisan rooms:backfill-commissions --dry-run

# Apply the changes
php artisan rooms:backfill-commissions
```

✅ Your existing rooms now have commission calculations.

**What this does:**
- Takes current `price_per_night` as base price
- Adds 10% commission
- Updates final price guests will see

**Example:**
```
Before: price_per_night = 2500 Nu.
After:  base_price = 2500 Nu.
        commission = 250 Nu. (10%)
        final_price = 2750 Nu.
```

---

### Step 3: Add Routes (1 min)

Add these routes to your routes file (`routes/web.php` or `routes/new-web-routes.php`):

```php
// Admin Commission Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/commissions', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{payout}', [App\Http\Controllers\Admin\CommissionController::class, 'show'])->name('commissions.show');
    Route::post('/commissions/generate', [App\Http\Controllers\Admin\CommissionController::class, 'generatePayouts'])->name('commissions.generate');
    Route::get('/commissions/{payout}/payout', [App\Http\Controllers\Admin\CommissionController::class, 'payoutForm'])->name('commissions.payout-form');
    Route::post('/commissions/{payout}/mark-paid', [App\Http\Controllers\Admin\CommissionController::class, 'markAsPaid'])->name('commissions.mark-paid');
    Route::get('/commissions-report/earnings', [App\Http\Controllers\Admin\CommissionController::class, 'earnings'])->name('commissions.earnings');
    Route::get('/commissions-report/hotels', [App\Http\Controllers\Admin\CommissionController::class, 'hotelReport'])->name('commissions.hotel-report');
});

// Owner Revenue Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/revenue', [App\Http\Controllers\Owner\RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/{payout}', [App\Http\Controllers\Owner\RevenueController::class, 'show'])->name('revenue.show');
    Route::get('/revenue/report/monthly', [App\Http\Controllers\Owner\RevenueController::class, 'monthlyReport'])->name('revenue.monthly-report');
});
```

---

### Step 4: Register Service (1 min)

Add the CommissionService to your service providers (`config/app.php`):

```php
'providers' => [
    // ... other providers
    App\Providers\AppServiceProvider::class,
],
```

Then in `app/Providers/AppServiceProvider.php`:

```php
public function register()
{
    $this->app->singleton(\App\Services\CommissionService::class, function ($app) {
        return new \App\Services\CommissionService();
    });
}
```

---

### Step 5: Backfill Bookings (Optional - 1 min)

If you have existing bookings:

```bash
# Check first
php artisan bookings:backfill-commissions --dry-run

# Apply
php artisan bookings:backfill-commissions
```

✅ Existing bookings now have commission records.

---

## ✅ You're Done!

### Test It Out

#### 1. Create a New Room

1. Login as hotel owner
2. Go to Rooms → Add New Room
3. Enter base price: **2500**
4. Watch it auto-calculate:
   - Commission: 250
   - Final Price: 2750
5. Save

#### 2. View Commission Dashboard (Admin)

1. Login as admin
2. Navigate to: `/admin/commissions`
3. You'll see platform statistics and hotel payouts

#### 3. View Revenue Dashboard (Owner)

1. Login as owner
2. Navigate to: `/owner/revenue`
3. You'll see your earnings and commission breakdown

---

## 📊 Generate Monthly Payouts

Run this at the end of each month:

```bash
# Generate for last month
php artisan payouts:generate

# Or specify month
php artisan payouts:generate --year=2024 --month=3
```

This creates monthly commission reports for all hotels.

---

## 🎯 What Changed?

### For Hotel Owners

**Before:**
- Enter "Price Per Night" → Guest sees that price

**After:**
- Enter "Base Price" (your earning) → System adds 10% → Guest sees final price
- You can track your monthly revenue and commission

### For Guests

**No Change!**
- They see the final price (includes commission)
- Booking process remains the same

### For Admins

**New Features:**
- Commission dashboard
- Monthly payout reports
- Platform earnings tracking
- Mark payouts as paid

---

## 🔧 Quick Configuration

### Change Commission Rate

Default is 10%. To change:

1. Update default in migration: `$table->decimal('commission_rate', 5, 2)->default(10.00);`
2. Or set per room when creating/editing

### Customize Views

View files are in:
- `resources/views/admin/commissions/` (admin views)
- `resources/views/owner/revenue/` (owner views)
- `resources/views/rooms/` (room forms - already updated)

---

## 🆘 Troubleshooting

### Issue: "Commission fields not found"

**Solution:** Run migrations
```bash
php artisan migrate
```

### Issue: "Existing rooms show 0.00 commission"

**Solution:** Backfill rooms
```bash
php artisan rooms:backfill-commissions
```

### Issue: "Route not found"

**Solution:** Add routes to your routes file and clear cache
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: "CommissionService not found"

**Solution:** Run composer autoload
```bash
composer dump-autoload
```

---

## 📱 Quick Commands Reference

```bash
# Migrations
php artisan migrate

# Backfill existing data
php artisan rooms:backfill-commissions
php artisan bookings:backfill-commissions

# Generate monthly payouts
php artisan payouts:generate
php artisan payouts:generate --year=2024 --month=3
php artisan payouts:generate --year=2024 --all-months

# Cache clearing (after code changes)
php artisan route:clear
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

---

## 🎉 Next Steps

1. ✅ **Test room creation** with commission calculation
2. ✅ **Create a test booking** to see commission tracking
3. ✅ **Generate payouts** for current/last month
4. ✅ **Check admin dashboard** to view reports
5. ✅ **Check owner dashboard** to view revenue

---

## 📚 Need More Details?

See **COMMISSION_SYSTEM_GUIDE.md** for:
- Detailed workflow explanations
- Database schema documentation
- API reference
- Advanced configuration
- Troubleshooting guide

---

**Setup Time:** ~5 minutes
**Difficulty:** Easy
**Status:** Production Ready ✅
