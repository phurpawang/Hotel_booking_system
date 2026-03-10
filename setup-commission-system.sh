#!/bin/bash

echo "========================================"
echo "Commission System Setup"
echo "Bhutan Hotel Booking System"
echo "========================================"
echo ""

echo "[1/5] Running database migrations..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo "ERROR: Migration failed!"
    exit 1
fi
echo "✓ Migrations completed successfully"
echo ""

echo "[2/5] Backfilling existing rooms with commission data..."
php artisan rooms:backfill-commissions
if [ $? -ne 0 ]; then
    echo "ERROR: Room backfill failed!"
    exit 1
fi
echo "✓ Rooms backfilled successfully"
echo ""

echo "[3/5] Backfilling existing bookings (optional)..."
read -p "Do you want to backfill existing bookings? (y/n): " proceed
if [ "$proceed" = "y" ] || [ "$proceed" = "Y" ]; then
    php artisan bookings:backfill-commissions --payment-method=pay_online
    echo "✓ Bookings backfilled successfully"
else
    echo "⊘ Skipped booking backfill"
fi
echo ""

echo "[4/5] Generating monthly payouts..."
php artisan payouts:generate
echo "✓ Payouts generated"
echo ""

echo "[5/5] Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
composer dump-autoload
echo "✓ Caches cleared"
echo ""

echo "========================================"
echo "✓ Setup Complete!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Test room creation at: /owner/rooms/create"
echo "2. View admin dashboard at: /admin/commissions"
echo "3. View owner dashboard at: /owner/revenue"
echo ""
echo "Documentation:"
echo "- Quick Start: COMMISSION_QUICK_START.md"
echo "- Full Guide: COMMISSION_SYSTEM_GUIDE.md"
echo "- Summary: COMMISSION_IMPLEMENTATION_SUMMARY.md"
echo ""
