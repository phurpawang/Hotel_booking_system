<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class BackfillRoomCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:backfill-commissions 
                            {--rate=10 : Commission rate to apply (default: 10%)}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill commission data for existing rooms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $commissionRate = $this->option('rate');
        $isDryRun = $this->option('dry-run');

        $this->info("Starting room commission backfill...");
        $this->info("Commission Rate: {$commissionRate}%");
        
        if ($isDryRun) {
            $this->warn("DRY RUN MODE - No changes will be made");
        }

        // Get all rooms that need commission data
        $rooms = Room::whereNull('base_price')
                    ->orWhereNull('commission_rate')
                    ->orWhereNull('final_price')
                    ->get();

        if ($rooms->isEmpty()) {
            $this->info("No rooms need backfilling. All rooms already have commission data.");
            return 0;
        }

        $this->info("Found {$rooms->count()} rooms to update.");
        
        $bar = $this->output->createProgressBar($rooms->count());
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($rooms as $room) {
            try {
                // Use existing price_per_night as base price
                $basePrice = $room->price_per_night;
                
                $calculated = Room::calculateCommission($basePrice, $commissionRate);

                if (!$isDryRun) {
                    $room->update([
                        'base_price' => $calculated['base_price'],
                        'commission_rate' => $calculated['commission_rate'],
                        'commission_amount' => $calculated['commission_amount'],
                        'final_price' => $calculated['final_price'],
                        'price_per_night' => $calculated['final_price'],
                    ]);
                }

                $updated++;
                
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->line("Room #{$room->room_number} ({$room->hotel->name}):");
                    $this->line("  Base Price: {$calculated['base_price']}");
                    $this->line("  Commission: {$calculated['commission_amount']}");
                    $this->line("  Final Price: {$calculated['final_price']}");
                }

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error updating room #{$room->id}: {$e->getMessage()}");
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
                ['Total Processed', $rooms->count()],
                ['Successfully Updated', $updated],
                ['Errors', $errors],
            ]
        );

        if ($isDryRun) {
            $this->warn("This was a DRY RUN. No changes were made to the database.");
            $this->info("Run without --dry-run to apply changes.");
        }

        return 0;
    }
}
