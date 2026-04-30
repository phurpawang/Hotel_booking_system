<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateMissingCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:regenerate-missing';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create missing commission records for paid bookings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $commissionService = app(CommissionService::class);
        
        // Find all paid bookings without commission records
        $paidBookings = Booking::where('payment_status', 'PAID')
            ->doesntHave('commission')
            ->get();

        if ($paidBookings->isEmpty()) {
            $this->info('✓ No missing commissions found. All paid bookings have commission records.');
            return Command::SUCCESS;
        }

        $successCount = 0;
        $failCount = 0;

        $this->info("Found {$paidBookings->count()} bookings with missing commission records.\n");

        foreach ($paidBookings as $booking) {
            try {
                DB::beginTransaction();

                // Create commission record
                $paymentMethodType = $booking->payment_method_type ?? 
                    ($booking->payment_method === 'ONLINE' ? 'pay_online' : 'pay_at_hotel');
                
                $commissionService->createBookingCommission($booking, $paymentMethodType);

                DB::commit();
                
                $this->line("✓ Created commission for booking {$booking->booking_id} (ID: {$booking->id})");
                $successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("✗ Failed for booking {$booking->booking_id}: {$e->getMessage()}");
                $failCount++;
            }
        }

        $this->newLine();
        $this->info("Commission regeneration complete!");
        $this->info("Successfully created: {$successCount}");
        $this->error("Failed: {$failCount}");

        return $failCount === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
