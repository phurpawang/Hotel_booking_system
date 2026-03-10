<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LinkExistingGuestsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:link-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create guest user records for existing bookings and link them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to link existing bookings to guest users...');

        // Get all bookings without a user_id
        $bookings = Booking::whereNull('user_id')
            ->whereNotNull('guest_email')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No bookings found without user_id. All bookings are already linked!');
            return 0;
        }

        $this->info('Found ' . $bookings->count() . ' bookings to process.');

        $created = 0;
        $linked = 0;
        $bar = $this->output->createProgressBar($bookings->count());

        foreach ($bookings as $booking) {
            // Create or find guest user by email
            $guest = User::firstOrCreate(
                ['email' => $booking->guest_email],
                [
                    'name' => $booking->guest_name,
                    'mobile' => $booking->guest_phone,
                    'role' => 'GUEST',
                    'password' => Hash::make('guest123'),
                    'status' => 'active',
                ]
            );

            if ($guest->wasRecentlyCreated) {
                $created++;
            }

            // Link booking to guest user
            $booking->update(['user_id' => $guest->id]);
            $linked++;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ Created {$created} new guest users");
        $this->info("✓ Linked {$linked} bookings to guest users");
        $this->info('Done!');

        return 0;
    }
}
