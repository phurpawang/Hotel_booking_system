@echo off
REM Bhutan Hotel Booking System - Setup Script (Windows)
REM This script helps set up the restructured authentication system

echo ================================================
echo BHBS - Restructured Authentication Setup
echo ================================================
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo ERROR: artisan file not found. Please run this script from the Laravel root directory.
    pause
    exit /b 1
)

echo [OK] Laravel project detected
echo.

REM Step 1: Clear cache
echo Step 1: Clearing application cache...
call php artisan cache:clear
call php artisan config:clear
call php artisan view:clear
call php artisan route:clear
echo [OK] Cache cleared
echo.

REM Step 2: Create storage link
echo Step 2: Creating storage link...
call php artisan storage:link
echo [OK] Storage link created
echo.

REM Step 3: Run migrations
echo Step 3: Running database migrations...
echo WARNING: This will drop and recreate hotels and users tables!
set /p CONFIRM="Continue? (yes/no): "
if /i "%CONFIRM%"=="yes" (
    call php artisan migrate
    echo [OK] Migrations completed
) else (
    echo [CANCELLED] Migration cancelled. You will need to run migrations manually.
)
echo.

REM Step 4: Create admin account reminder
echo Step 4: Creating admin account...
echo Please run this SQL query in phpMyAdmin or MySQL:
echo.
echo INSERT INTO admins (username, password, created_at, updated_at^)
echo VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(^), NOW(^)^)
echo ON DUPLICATE KEY UPDATE username=username;
echo.
echo Admin credentials:
echo Username: admin
echo Password: password
echo WARNING: CHANGE THIS PASSWORD IN PRODUCTION!
echo.
pause

REM Step 5: Update routes
echo Step 5: Updating routes...
if exist "routes\web.php" (
    copy routes\web.php routes\web-backup-%date:~-4,4%%date:~-10,2%%date:~-7,2%.php
    echo [OK] Backed up existing web.php
)

if exist "routes\新-web-routes.php" (
    copy routes\new-web-routes.php routes\web.php
    echo [OK] Routes updated
) else (
    echo [WARNING] new-web-routes.php not found. You'll need to update routes manually.
)
echo.

REM Complete
echo ================================================
echo Setup Complete!
echo ================================================
echo.
echo Next steps:
echo 1. Start your development server: php artisan serve
echo 2. Admin login: http://localhost:8000/admin/login
echo    - Username: admin
echo    - Password: password
echo 3. Hotel registration: http://localhost:8000/hotel/register
echo.
echo Refer to RESTRUCTURE_README.md for complete documentation.
echo.
pause
