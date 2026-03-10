<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Guest;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class MigrateGuestsFromUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:migrate-from-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate guest users from users table to guests table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting guest migration from users to guests table...');

        // Get all guest users
        $guestUsers = User::where('role', 'GUEST')->get();

        if ($guestUsers->isEmpty()) {
            $this->info('No guest users found in users table.');
            return 0;
        }

        $this->info('Found ' . $guestUsers->count() . ' guest users to migrate.');

        $migrated = 0;
        $bar = $this->output->createProgressBar($guestUsers->count());

        DB::transaction(function() use ($guestUsers, &$migrated, $bar) {
            foreach ($guestUsers as $user) {
                // Create guest record
                $guest = Guest::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'mobile' => $user->mobile,
                    'address' => $user->address,
                    'profile_photo' => $user->profile_photo,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);

                // Update all bookings to use guest_id instead of user_id
                Booking::where('user_id', $user->id)->update(['guest_id' => $guest->id]);

                $migrated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ Migrated {$migrated} guests to guests table");
        $this->info("✓ Updated bookings to use guest_id");
        
        // Ask if we should delete guest users
        if ($this->confirm('Do you want to delete guest users from users table?', true)) {
            $deleted = User::where('role', 'GUEST')->delete();
            $this->info("✓ Deleted {$deleted} guest users from users table");
        }

        $this->info('Migration complete!');

        return 0;
    }
}
