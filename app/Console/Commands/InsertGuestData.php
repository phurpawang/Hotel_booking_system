<?php

namespace App\Console\Commands;

use App\Models\Guest;
use Illuminate\Console\Command;

class InsertGuestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:insert-from-bookings';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Insert guest data from booking records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Guest data extracted from bookings
        $guests = [
            [
                'name' => 'Sonam Choden',
                'email' => '11606003606@rim.edu.bt',
                'phone' => '77777777',
                'status' => 'active'
            ],
            [
                'name' => 'sangay dorji',
                'email' => 'sangay.dorji@rim.edu.bt',
                'phone' => '77777777',
                'status' => 'active'
            ],
            [
                'name' => 'sangay yeshi',
                'email' => 'sangay.yeshi@rim.edu.bt',
                'phone' => '77777777',
                'status' => 'active'
            ],
            [
                'name' => 'Jample Dechen',
                'email' => 'jample.dechen@rim.edu.bt',
                'phone' => '17573956',
                'status' => 'active'
            ],
            [
                'name' => 'Sangay Penjor',
                'email' => 'sangay.penjor@rim.edu.bt',
                'phone' => '77777777',
                'status' => 'active'
            ],
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($guests as $guestData) {
            // Check if guest already exists by email
            $existing = Guest::where('email', $guestData['email'])->first();

            if (!$existing) {
                try {
                    $guest = Guest::create($guestData);
                    $this->info("✓ Created guest: {$guest->name} (ID: {$guest->id})");
                    $createdCount++;
                } catch (\Exception $e) {
                    $this->error("✗ Failed to create {$guestData['name']}: {$e->getMessage()}");
                }
            } else {
                $this->warn("⊘ Guest already exists: {$existing->name} (ID: {$existing->id})");
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info("Guest insertion complete!");
        $this->info("Created: {$createdCount} | Skipped: {$skippedCount}");

        return Command::SUCCESS;
    }
}
