<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelRejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(Hotel $hotel, $reason = null)
    {
        $this->hotel = $hotel;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hotel Registration Rejected - BHBS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hotel-rejection',
        );
    }
}
