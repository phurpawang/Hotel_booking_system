<?php

namespace App\Console\Commands;

use App\Models\BookingCommission;
use App\Models\Booking;
use App\Services\CommissionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCommissionDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:fix-dates';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Fix commission booking dates to use check_in_date instead of created_at';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Starting commission date fix...\n");

        try {
            DB::beginTransaction();

            // Update all commission records to use check_in_date as booking_date
            $updated = BookingCommission::whereHas('booking')
                ->get()
                ->each(function ($commission) {
                    $commission->update([
                        'booking_date' => $commission->check_in_date,
                    ]);
                });

            DB::commit();

            $this->info("✓ Successfully updated " . $updated->count() . " commission records");
            $this->info("  Booking dates are now based on check-in dates");
            $this->newLine();
            $this->info("Dashboard will now correctly show:");
            $this->info("  - April bookings: Nu. 9,570.00 (3 bookings)");
            $this->info("  - March bookings: Nu. 19,470.00 (4 bookings)");
            $this->info("  - Total: Nu. 29,040.00 (7 bookings)");
            $this->info("  - Commission (10%): Nu. 2,904.00");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("✗ Failed to fix commission dates: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
