# Hotel Login Credentials

## Database Changes Applied
✅ Removed `pin` column from `users` table
✅ Updated passwords for 3 hotels using bcrypt hashing
✅ Password-based authentication system is now active

---

## Hotel Login Details

### Hotel 1: Hotel Park (HT001)
- **Hotel ID**: `HT001`
- **Email**: `owner@hotelpark.com`
- **Password**: `Tedday@123`
- **Login URL**: http://127.0.0.1:8000/hotel/login

---

### Hotel 2: Druk Hotel (HT002)
- **Hotel ID**: `HT002`
- **Email**: `owner@drukhotel.com`
- **Password**: `Tedday@345`
- **Login URL**: http://127.0.0.1:8000/hotel/login

---

### Hotel 3: Hotel ZhuSA (HTL000003)
- **Hotel ID**: `HTL000003`
- **Email (Owner 1)**: `pwangchuk282@gmail.com`
- **Password**: `Tedday@678`
- **Email (Owner 2)**: `owner@hotelzhusa.com`
- **Password**: `Tedday@678`
- **Login URL**: http://127.0.0.1:8000/hotel/login

---

## Login Instructions

1. Go to: http://127.0.0.1:8000/hotel/login
2. Enter:
   - **Hotel ID** (e.g., HT001)
   - **Email** (owner email)
   - **Password** (as shown above)
3. Click "Login"

---

## Staff Users (Managers & Receptionists)

### Hotel Park (HT001)
- **Manager**: manager@hotelpark.com (Password needs to be set)
- **Reception**: reception@hotelpark.com (Password needs to be set)

### Druk Hotel (HT002)
- **Manager**: manager@drukhotel.com (Password needs to be set)
- **Reception**: reception@drukhotel.com (Password needs to be set)

### Hotel ZhuSA (HTL000003)
- **Manager**: manager@hotelzhusa.com (Password needs to be set)
- **Reception**: reception@hotelzhusa.com (Password needs to be set)

---

## Migration Applied

**File**: `2026_03_04_082857_remove_pin_column_from_users_table.php`

**Actions**:
1. Dropped `pin` column from `users` table
2. Updated password for hotel_id=1 owner to bcrypt('Tedday@123')
3. Updated password for hotel_id=2 owner to bcrypt('Tedday@345')
4. Updated password for hotel_id=3 owners to bcrypt('Tedday@678')

---

## Database Schema

**users table** now has:
- ✅ `password` (varchar 255) - bcrypt hashed
- ❌ `pin` (removed)

**Authentication Method**: Hotel ID + Email + Password
