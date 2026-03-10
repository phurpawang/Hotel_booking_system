<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Illuminate\Console\Command;

class BackfillBookingCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:backfill-commissions 
                            {--payment-method=pay_online : Default payment method for existing bookings}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill commission data for existing bookings';

    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        parent::__construct();
        $this->commissionService = $commissionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentMethod = $this->option('payment-method');
        $isDryRun = $this->option('dry-run');

        $this->info("Starting booking commission backfill...");
        $this->info("Default Payment Method: {$paymentMethod}");
        
        if ($isDryRun) {
            $this->warn("DRY RUN MODE - No changes will be made");
        }

        // Get bookings that don't have commission records
        $bookings = Booking::whereDoesntHave('commission')
                          ->whereIn('status', ['CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT'])
                          ->with('room')
                          ->get();

        if ($bookings->isEmpty()) {
            $this->info("No bookings need backfilling. All bookings already have commission data.");
            return 0;
        }

        $this->info("Found {$bookings->count()} bookings to update.");
        
        $bar = $this->output->createProgressBar($bookings->count());
        $bar->start();

        $created = 0;
        $errors = 0;

        foreach ($bookings as $booking) {
            try {
                if (!$booking->room) {
                    $this->newLine();
                    $this->warn("Skipping booking #{$booking->id} - Room not found");
                    continue;
                }

                if (!$isDryRun) {
                    $this->commissionService->createBookingCommission($booking, $paymentMethod);
                }

                $created++;

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error creating commission for booking #{$booking->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info("Backfill completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Total Processed', $bookings->count()],
                ['Successfully Created', $created],
                ['Errors', $errors],
            ]
        );

        if ($isDryRun) {
            $this->warn("This was a DRY RUN. No changes were made to the database.");
            $this->info("Run without --dry-run to apply changes.");
        } else {
            $this->info("Next step: Generate monthly payouts using 'php artisan payouts:generate'");
        }

        return 0;
    }
}
