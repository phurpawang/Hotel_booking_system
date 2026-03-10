<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\BookingConfirmation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestBookingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-booking {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test booking confirmation email by sending to specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing booking confirmation email to: {$email}");
        
        // Get the most recent booking or create a dummy one for testing
        $booking = Booking::with(['hotel', 'room'])->latest()->first();
        
        if (!$booking) {
            $this->error('No bookings found in database. Please create a booking first.');
            return 1;
        }
        
        $this->info("Using booking ID: {$booking->booking_id}");
        
        try {
            Mail::to($email)->send(new BookingConfirmation($booking));
            $this->info('✓ Email sent successfully!');
            $this->info('Check your inbox at: ' . $email);
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->line('Common solutions:');
            $this->line('1. Check your .env MAIL_* settings');
            $this->line('2. Verify Gmail App Password is correct');
            $this->line('3. Ensure 2FA is enabled on Gmail account');
            $this->line('4. Check storage/logs/laravel.log for details');
            return 1;
        }
    }
}
