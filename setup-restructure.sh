#!/bin/bash

# Bhutan Hotel Booking System - Setup Script
# This script helps set up the restructured authentication system

echo "================================================"
echo "BHBS - Restructured Authentication Setup"
echo "================================================"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

echo "✓ Laravel project detected"
echo ""

# Step 1: Backup database
echo "Step 1: Creating database backup..."
read -p "Enter MySQL username (default: root): " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}

read -sp "Enter MySQL password: " MYSQL_PASS
echo ""

read -p "Enter database name (default: bhbs): " DB_NAME
DB_NAME=${DB_NAME:-bhbs}

BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u$MYSQL_USER -p$MYSQL_PASS $DB_NAME > $BACKUP_FILE
if [ $? -eq 0 ]; then
    echo "✓ Database backed up to $BACKUP_FILE"
else
    echo "⚠️  Warning: Could not create database backup. Continue anyway? (y/n)"
    read -p "" CONTINUE
    if [ "$CONTINUE" != "y" ]; then
        exit 1
    fi
fi
echo ""

# Step 2: Clear cache
echo "Step 2: Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo "✓ Cache cleared"
echo ""

# Step 3: Run migrations
echo "Step 3: Running database migrations..."
echo "⚠️  WARNING: This will drop and recreate hotels and users tables!"
read -p "Continue? (yes/no): " CONFIRM
if [ "$CONFIRM" = "yes" ]; then
    php artisan migrate
    echo "✓ Migrations completed"
else
    echo "❌ Migration cancelled. You will need to run migrations manually."
fi
echo ""

# Step 4: Create storage link
echo "Step 4: Creating storage link..."
php artisan storage:link
echo "✓ Storage link created"
echo ""

# Step 5: Set permissions
echo "Step 5: Setting file permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✓ Permissions set"
echo ""

# Step 6: Create admin account
echo "Step 6: Creating admin account..."
mysql -u$MYSQL_USER -p$MYSQL_PASS $DB_NAME <<EOF
INSERT INTO admins (username, password, created_at, updated_at) 
VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW())
ON DUPLICATE KEY UPDATE username=username;
EOF

if [ $? -eq 0 ]; then
    echo "✓ Admin account created"
    echo "  Username: admin"
    echo "  Password: password"
    echo "  ⚠️  CHANGE THIS PASSWORD IN PRODUCTION!"
else
    echo "⚠️  Could not create admin account. You may need to do this manually."
fi
echo ""

# Step 7: Update routes
echo "Step 7: Updating routes..."
if [ -f "routes/web.php" ]; then
    cp routes/web.php routes/web-backup-$(date +%Y%m%d_%H%M%S).php
    echo "✓ Backed up existing web.php"
fi

if [ -f "routes/new-web-routes.php" ]; then
    cp routes/new-web-routes.php routes/web.php
    echo "✓ Routes updated"
else
    echo "⚠️  new-web-routes.php not found. You'll need to update routes manually."
fi
echo ""

# Complete
echo "================================================"
echo "Setup Complete!"
echo "================================================"
echo ""
echo "Next steps:"
echo "1. Start your development server: php artisan serve"
echo "2. Admin login: http://localhost:8000/admin/login"
echo "   - Username: admin"
echo "   - Password: password"
echo "3. Hotel registration: http://localhost:8000/hotel/register"
echo ""
echo "Refer to RESTRUCTURE_README.md for complete documentation."
echo ""
