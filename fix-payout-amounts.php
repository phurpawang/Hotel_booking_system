<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Fixing Hotel Payout Payment Method Breakdown...\n";
echo "==============================================\n\n";

// Get all hotel payouts
$payouts = DB::table('hotel_payouts')->get();

echo "Found " . $payouts->count() . " payout record(s).\n\n";

foreach ($payouts as $payout) {
    echo "Processing Payout ID: {$payout->id}\n";
    echo "  Hotel ID: {$payout->hotel_id}\n";
    echo "  Period: " . date('F', mktime(0, 0, 0, $payout->month, 1)) . " {$payout->year}\n";
    
    // Get commissions for this payout period
    $commissions = DB::table('booking_commissions')
        ->where('hotel_id', $payout->hotel_id)
        ->whereYear('booking_date', $payout->year)
        ->whereMonth('booking_date', $payout->month)
        ->get();
    
    if ($commissions->isEmpty()) {
        echo "  ⚠ No commissions found for this payout!\n\n";
        continue;
    }
    
    // Calculate payment method breakdown
    $payOnlineAmount = 0;
    $payAtHotelAmount = 0;
    
    foreach ($commissions as $commission) {
        if ($commission->payment_method == 'pay_online') {
            $payOnlineAmount += $commission->final_amount;
        } else {
            $payAtHotelAmount += $commission->final_amount;
        }
    }
    
    // Update payout record
    DB::table('hotel_payouts')
        ->where('id', $payout->id)
        ->update([
            'pay_online_amount' => $payOnlineAmount,
            'pay_at_hotel_amount' => $payAtHotelAmount,
        ]);
    
    echo "  ✓ OLD - Online: Nu. " . number_format($payout->pay_online_amount, 2) . ", Hotel: Nu. " . number_format($payout->pay_at_hotel_amount, 2) . "\n";
    echo "  ✓ NEW - Online: Nu. " . number_format($payOnlineAmount, 2) . ", Hotel: Nu. " . number_format($payAtHotelAmount, 2) . "\n";
    echo "  ✓ Updated successfully!\n\n";
}

echo "==============================================\n";
echo "✓ All payout records have been updated!\n";
echo "==============================================\n\n";

// Show summary
echo "Summary:\n";
echo "--------\n";

$allPayouts = DB::table('hotel_payouts')->get();
$totalOnline = $allPayouts->sum('pay_online_amount');
$totalHotel = $allPayouts->sum('pay_at_hotel_amount');

echo "Total Online Payments (Platform): Nu. " . number_format($totalOnline, 2) . "\n";
echo "Total Hotel Payments (Cash/Card/Bank): Nu. " . number_format($totalHotel, 2) . "\n";
echo "Total Combined: Nu. " . number_format($totalOnline + $totalHotel, 2) . "\n";

