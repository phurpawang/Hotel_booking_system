# Dashboard Testing Quick Guide 🚀

**BHBS Commission System - Dashboard Integration**

---

## 🎯 Quick Testing Steps

### **1. Owner Dashboard Test**

1. **Login as Owner**
   - Navigate to: `http://localhost/BHBS/public/owner/dashboard`
   
2. **Check Commission Section** (Purple gradient card)
   - ✅ Should display: Guest Payments, Commission, Net Earning, Payout Status
   
3. **Check Recent Payouts Table**
   - ✅ Should show last 6 months with commission breakdown
   
4. **Check Recent Bookings**
   - ✅ Should show payment method and commission per booking
   
5. **Navigate to Revenue Page**
   - Click "View Full Report" or sidebar link: Revenue & Commission
   - Expected route: `owner/revenue/index` (controller already exists)

---

### **2. Manager Dashboard Test**

1. **Login as Manager**
   - Navigate to: `http://localhost/BHBS/public/manager/dashboard`
   
2. **Check Commission Info Section** (Blue gradient card)
   - ✅ Should display: Total bookings, Guest payments, Commission
   - ✅ Should show lock icon with "Read Only" note
   
3. **Check Recent Bookings**
   - ✅ Should show payment method badges (Online/At Hotel)
   
4. **Verify Rooms Page**
   - Navigate to: Manage Rooms
   - ✅ Should see Base Price, Commission, Final Price columns

---

### **3. Receptionist Dashboard Test**

1. **Login as Receptionist**
   - Navigate to: `http://localhost/BHBS/public/receptionist/dashboard`
   
2. **Check Payment Status Overview** (Info card)
   - ✅ Should show: Paid Online count, Pay at Hotel count
   
3. **Check Today's Arrivals**
   - ✅ Bookings should show payment badges
   - ✅ "Pay at Hotel" bookings should show amount due
   
4. **Check Today's Departures**
   - ✅ Should show room price and payment method

---

### **4. Admin Dashboard Test**

1. **Login as Admin**
   - Navigate to: `http://localhost/BHBS/public/admin/dashboard`
   
2. **Check Platform Performance Section**
   - ✅ Should display: Bookings, Guest Payments, Commission, Hotel Payouts
   
3. **Check Pending Payouts Table**
   - ✅ Should list hotels with unpaid payouts
   - ✅ Should have "View" and "Process" action buttons
   
4. **Check Recent Bookings**
   - ✅ Should show payment method and commission columns

---

## 🧪 Test Scenarios

### **Scenario 1: Create a Room with Commission**

```
1. Login as Owner
2. Go to: Rooms → Add New Room
3. Fill in:
   - Room Number: 401
   - Room Type: Deluxe
   - Base Price: 3000
4. Observe real-time calculation:
   - Commission: 300 (10%)
   - Final Price: 3300
5. Save and verify in Rooms list
```

**Expected Result:**
- Room appears in list with Base=3000, Commission=300, Final=3300

---

### **Scenario 2: Create a Booking and Track Commission**

```
1. Create a booking for Room 401
2. Select Payment Method: "Pay Online" or "Pay at Hotel"
3. Complete booking
4. Check Owner Dashboard → Recent Bookings
   - ✅ Should show payment method badge
   - ✅ Should show commission amount
5. Check Receptionist Dashboard
   - ✅ Should appear in payment status tracking
```

---

### **Scenario 3: View Monthly Payout**

```
1. Login as Owner
2. Go to: Revenue & Commission
3. View current month summary
4. Login as Admin  
5. Go to admin/commissions
6. Generate payout for current month
7. Return to Owner dashboard
   - ✅ Payout status should update
```

---

## 🔍 Validation Tests

### **Commission Calculation Test**

| Base Price | Commission Rate | Commission Amount | Final Price |
|-----------|----------------|------------------|-------------|
| 1,000 | 10% | 100 | 1,100 |
| 2,500 | 10% | 250 | 2,750 |
| 5,000 | 10% | 500 | 5,500 |

**Test in:** Rooms → Add/Edit Room form

---

### **Payment Method Display Test**

**Owner Dashboard:**
- [ ] Badge shows "Online" (blue) or "At Hotel" (purple)
- [ ] Commission amount displays correctly
- [ ] Hotel earning displays correctly

**Manager Dashboard:**
- [ ] Payment method badges visible
- [ ] Commission section marked as "View Only"

**Receptionist Dashboard:**
- [ ] "Pay at Hotel" highlighted in yellow
- [ ] Amount due shown for pending payments

**Admin Dashboard:**
- [ ] Payment method column in bookings table
- [ ] Commission amount column populated

---

## ⚡ Quick Commands

### **1. Generate Test Data**

```bash
# Backfill existing rooms with commission
php artisan rooms:backfill --dry-run
php artisan rooms:backfill

# Backfill existing bookings with commission
php artisan bookings:backfill --dry-run
php artisan bookings:backfill
```

### **2. Generate Monthly Payouts**

```bash
# Generate payout for current month
php artisan payouts:generate

# Generate for specific month
php artisan payouts:generate --year=2026 --month=3

# Generate all historical months
php artisan payouts:generate --all-months
```

### **3. Clear Cache** (if changes don't appear)

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 🐛 Troubleshooting

### **Issue: Commission not showing**

**Solution:**
```bash
# Run migrations
php artisan migrate

# Backfill rooms
php artisan rooms:backfill

# Clear cache
php artisan cache:clear
```

---

### **Issue: Payout status shows "Not Generated Yet"**

**Solution:**
```bash
# Generate payouts for current month
php artisan payouts:generate
```

---

### **Issue: Dashboard shows errors**

**Check:**
1. ✅ CommissionService is injected in controller
2. ✅ Routes are properly defined in `routes/web.php`
3. ✅ Models have commission relationships
4. ✅ Commission fields exist in database

**Verify Tables:**
```sql
DESCRIBE rooms;  -- Check for base_price, commission_amount, final_price
DESCRIBE bookings;  -- Check for commission fields
DESCRIBE booking_commissions;  -- Should exist
DESCRIBE hotel_payouts;  -- Should exist
```

---

## ✅ Success Indicators

### **Owner Dashboard:**
- [x] Commission card displays with current month data
- [x] Recent payouts table shows 6 months
- [x] Recent bookings show payment methods
- [x] Revenue link in sidebar works

### **Manager Dashboard:**
- [x] Commission info section appears (read-only)
- [x] Recent bookings show payment badges
- [x] "View Only" note is visible

### **Receptionist Dashboard:**
- [x] Payment status overview displays
- [x] Today's arrivals show payment info
- [x] "Pay at Hotel" bookings highlighted

### **Admin Dashboard:**
- [x] Platform performance section displays
- [x] Pending payouts table visible
- [x] Recent bookings show commission

### **Rooms Page (All Roles):**
- [x] Base Price column
- [x] Commission (10%) column
- [x] Final Price column
- [x] Color-coded labels

---

## 📊 Expected Data Flow

```
1. Owner creates Room 401 with Base Price: Nu. 3,000
   ↓
2. System calculates:
   - Commission: Nu. 300 (10%)
   - Final Price: Nu. 3,300
   ↓
3. Guest books Room 401
   - Selects "Pay Online" or "Pay at Hotel"
   - Sees Final Price: Nu. 3,300
   ↓
4. Booking Commission Record Created:
   - Base Amount: Nu. 3,000 (hotel earning)
   - Commission: Nu. 300 (platform earning)
   - Final Amount: Nu. 3,300 (guest payment)
   ↓
5. End of Month:
   - Admin runs: php artisan payouts:generate
   - HotelPayout record created
   - Status: Pending
   ↓
6. Admin processes payout
   - Status changes to: Paid
   - Owner sees "Paid" badge on dashboard
```

---

## 🎓 Role Capabilities Summary

| Feature | Owner | Manager | Receptionist | Admin |
|---------|-------|---------|--------------|-------|
| **View Commission** | ✅ Full Access | 👁️ Read Only | ❌ No Access | ✅ Full Access |
| **View Payouts** | ✅ Own Hotel | ❌ No Access | ❌ No Access | ✅ All Hotels |
| **Edit Room Prices** | ✅ Yes | ⚠️ Limited | ❌ No | ❌ No |
| **See Payment Methods** | ✅ Yes | ✅ Yes | ✅ Yes | ✅ Yes |
| **Process Payouts** | ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Generate Payouts** | ❌ No | ❌ No | ❌ No | ✅ Yes |

---

## 🚀 Ready to Deploy!

Once all tests pass:
1. ✅ Commission calculations correct
2. ✅ All dashboards displaying properly
3. ✅ Payment methods tracking works
4. ✅ Payout generation functional
5. ✅ Role-based access confirmed

**System Status**: READY FOR PRODUCTION ✅

---

*Last Updated: March 9, 2026*  
*BHBS Commission System v1.0*
