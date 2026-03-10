# Commission-Based Booking System - Implementation Guide

## Overview

This guide explains how to set up and use the commission-based booking system for the Bhutan Hotel Booking System. The platform takes a **10% commission** from each booking, similar to Booking.com.

---

## 🚀 Installation & Setup

### Step 1: Run Database Migrations

Execute the migrations to add commission fields to your database:

```bash
php artisan migrate
```

This will:
- Add `base_price`, `commission_rate`, `commission_amount`, `final_price` to `rooms` table
- Create `booking_commissions` table
- Create `hotel_payouts` table
- Update `bookings` table with commission fields

### Step 2: Backfill Existing Rooms

Update existing rooms with commission data:

```bash
# Dry run first to see what will happen
php artisan rooms:backfill-commissions --dry-run

# Apply changes
php artisan rooms:backfill-commissions
```

**Important:** This command converts existing `price_per_night` values to `base_price` and adds the 10% commission.

### Step 3: Backfill Existing Bookings (Optional)

If you have existing bookings, create commission records for them:

```bash
# Dry run first
php artisan bookings:backfill-commissions --dry-run

# Apply changes
php artisan bookings:backfill-commissions --payment-method=pay_online
```

### Step 4: Generate Monthly Payouts

Generate payout reports for hotels:

```bash
# Generate for last month
php artisan payouts:generate

# Generate for specific month
php artisan payouts:generate --year=2024 --month=3

# Generate for all months in a year
php artisan payouts:generate --year=2024 --all-months
```

---

## 💰 How Commission System Works

### Room Pricing

When a hotel owner creates/edits a room:

1. **Owner enters Base Price**: This is what the hotel owner earns per night (e.g., 2500 Nu.)
2. **System calculates Commission**: 10% of base price (e.g., 250 Nu.)
3. **System calculates Final Price**: Base + Commission (e.g., 2750 Nu.)
4. **Guests see Final Price**: 2750 Nu. is displayed to guests

**Example:**
```
Base Price (Owner's earning): 2500 Nu.
Commission (10%):              250 Nu.
Final Price (Guest pays):     2750 Nu.
```

### Booking & Commission

When a booking is created:

1. System creates a `BookingCommission` record tracking:
   - Base amount (hotel's earning)
   - Commission amount (platform's earning)
   - Final amount (guest payment)
   - Payment method (pay_online / pay_at_hotel)

2. Commission status is set to `pending`

### Payment Methods

#### Pay Online
- Guest pays through the platform
- Platform holds the full amount
- Hotel receives payout monthly (base amount only)
- Commission stays with platform

#### Pay at Hotel
- Guest pays hotel directly
- Hotel records the booking
- Hotel owes commission to platform
- Hotel pays commission monthly

---

## 📊 Monthly Payout System

### How It Works

1. **System generates monthly reports** listing all bookings for each hotel
2. **Calculates totals:**
   - Total bookings count
   - Total guest payments
   - Total commission owed
   - Hotel payout amount

3. **For Pay Online bookings:**
   - Platform has already received payment
   - Platform pays hotel the base amount

4. **For Pay at Hotel bookings:**
   - Hotel has received payment
   - Hotel pays platform the commission amount

### Payout Status Flow

```
pending → processing → paid
```

- **Pending**: Awaiting admin action
- **Processing**: Admin has initiated payment
- **Paid**: Payment completed and verified

---

## 👨‍💼 Admin Dashboard Features

### Commission Dashboard

Access: **Admin Dashboard → Commissions**

Features:
- View platform earnings
- See all hotel payouts
- Filter by year/month
- Generate monthly reports
- Mark payouts as paid

### Monthly Report Generation

```bash
# Manually generate via command
php artisan payouts:generate --year=2024 --month=3

# Or use admin dashboard
Admin → Commissions → Generate Payouts
```

### Mark Payout as Paid

1. Go to specific hotel payout
2. Click "Mark as Paid"
3. Enter payment reference/notes
4. Submit
5. System updates commission status to "paid"

---

## 🏨 Hotel Owner Dashboard Features

### Revenue Dashboard

Access: **Owner Dashboard → Revenue**

Owner can view:
- Monthly revenue breakdown
- Total bookings
- Gross revenue (what guests paid)
- Commission deducted
- Net revenue (what owner earns)
- Pending payouts
- Payment history

### Revenue Summary

```
Total Bookings:        50
Gross Revenue:      137,500 Nu.  (what guests paid)
Commission:          12,500 Nu.  (platform's 10%)
Net Revenue:        125,000 Nu.  (owner's earning)
Pending Payout:      25,000 Nu.
Received Payout:    100,000 Nu.
```

---

## 🛠️ Technical Components

### Models

1. **Room** - Extended with commission fields
   - `base_price`, `commission_rate`, `commission_amount`, `final_price`
   - Automatic commission calculation on create/update

2. **BookingCommission** - Tracks commission per booking
   - Links to booking, hotel, room
   - Stores pricing breakdown
   - Tracks payment method and commission status

3. **HotelPayout** - Monthly payout records
   - One record per hotel per month
   - Aggregates all bookings for the period
   - Tracks payout status

### Services

**CommissionService** - Core business logic
- `createBookingCommission()` - Creates commission record for new booking
- `generateMonthlyPayout()` - Generates payout for hotel/month
- `markPayoutAsPaid()` - Updates payout and commission status
- `getPlatformStatistics()` - Platform earnings stats
- `getHotelRevenueSummary()` - Hotel revenue summary

### Controllers

1. **Admin\CommissionController** - Admin commission management
2. **Owner\RevenueController** - Owner revenue dashboard
3. **RoomController** - Updated with commission calculation

### Artisan Commands

1. `rooms:backfill-commissions` - Update existing rooms
2. `bookings:backfill-commissions` - Create commissions for existing bookings
3. `payouts:generate` - Generate monthly payout reports

---

## 📋 Database Schema

### Rooms Table (New Fields)
```sql
base_price           DECIMAL(10,2)  - Hotel's earning per night
commission_rate      DECIMAL(5,2)   - Commission percentage (default: 10.00)
commission_amount    DECIMAL(10,2)  - Calculated commission
final_price          DECIMAL(10,2)  - Price shown to guests
```

### Booking Commissions Table
```sql
id                   BIGINT
booking_id           BIGINT FK
hotel_id             BIGINT FK
room_id              BIGINT FK
base_amount          DECIMAL(10,2)  - Hotel's earning
commission_rate      DECIMAL(5,2)   - Rate at booking time
commission_amount    DECIMAL(10,2)  - Commission amount
final_amount         DECIMAL(10,2)  - Guest payment
payment_method       ENUM('pay_online', 'pay_at_hotel')
commission_status    ENUM('pending', 'paid')
booking_date         DATE
check_in_date        DATE
check_out_date       DATE
```

### Hotel Payouts Table
```sql
id                      BIGINT
hotel_id                BIGINT FK
year                    YEAR
month                   TINYINT (1-12)
total_bookings          INT
total_guest_payments    DECIMAL(12,2)
total_commission        DECIMAL(12,2)
hotel_payout_amount     DECIMAL(12,2)
pay_online_amount       DECIMAL(12,2)
pay_at_hotel_amount     DECIMAL(12,2)
payout_status           ENUM('pending', 'processing', 'paid', 'cancelled')
payout_date             DATE
payout_reference        VARCHAR
processed_by            BIGINT FK (user)
```

---

## 🔄 Workflow Examples

### Scenario 1: New Room Creation

1. Owner enters base price: **2500 Nu.**
2. JavaScript calculates in real-time:
   - Commission: 250 Nu. (10%)
   - Final Price: 2750 Nu.
3. Owner sees "Your Earning: 2500 Nu."
4. System saves all calculated fields

### Scenario 2: Guest Makes Booking (Pay Online)

1. Guest selects room (sees final price: 2750 Nu.)
2. Guest pays through platform
3. System creates:
   - Booking record
   - BookingCommission record with `payment_method=pay_online`
4. Commission status: `pending`

### Scenario 3: Monthly Payout Processing

1. Admin runs: `php artisan payouts:generate --year=2024 --month=3`
2. System aggregates all March bookings per hotel
3. Creates/updates HotelPayout records
4. Admin reviews and marks as paid
5. System updates all related commissions to `paid` status

---

## 🎯 Important Notes

### Commission Rate

- Default: **10%**
- Currently fixed in the system
- Can be customized per room if needed

### Guest Visibility

- Guests **only see final price**
- Commission breakdown is **internal** (admin/owner only)

### Monthly Payout Timing

- Run payout generation at month end
- Process payouts by 5th of following month
- Keep reference numbers for all transactions

### Backfill Commands

- Always run with `--dry-run` first
- Review output before applying changes
- Existing bookings default to `pay_online`

---

## 📞 Support & Troubleshooting

### Common Issues

**Issue: Existing rooms show no commission**
```bash
php artisan rooms:backfill-commissions
```

**Issue: Missing commission records**
```bash
php artisan bookings:backfill-commissions
```

**Issue: Payout totals don't match**
```bash
# Regenerate payouts for specific month
php artisan payouts:generate --year=2024 --month=3
```

### Verification Queries

```sql
-- Check room commission setup
SELECT room_number, base_price, commission_amount, final_price 
FROM rooms 
WHERE hotel_id = YOUR_HOTEL_ID;

-- Check commission records
SELECT COUNT(*), SUM(commission_amount), SUM(base_amount)
FROM booking_commissions
WHERE hotel_id = YOUR_HOTEL_ID;

-- Check payouts
SELECT * FROM hotel_payouts 
WHERE hotel_id = YOUR_HOTEL_ID 
ORDER BY year DESC, month DESC;
```

---

## ✅ Checklist for Going Live

- [ ] Run migrations
- [ ] Backfill existing rooms
- [ ] Backfill existing bookings (if any)
- [ ] Generate initial payouts
- [ ] Test room creation with commission calculation
- [ ] Test booking creation with commission tracking
- [ ] Verify admin commission dashboard
- [ ] Verify owner revenue dashboard
- [ ] Set up monthly payout schedule
- [ ] Train admin staff on payout processing

---

## 🔜 Future Enhancements

- Automated monthly payout generation (scheduler)
- Email notifications for payout status changes
- Variable commission rates per hotel/room
- CSV export of commission reports
- Integration with payment gateways
- Automated bank transfers

---

**Last Updated:** March 9, 2026
**Version:** 1.0.0
