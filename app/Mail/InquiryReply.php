<?php

namespace App\Mail;

use App\Models\HotelInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $hotelName;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelInquiry $inquiry, $hotelName)
    {
        $this->inquiry = $inquiry;
        $this->hotelName = $hotelName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Question Has Been Answered - ' . $this->hotelName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-reply',
            with: [
                'inquiry' => $this->inquiry,
                'hotelName' => $this->hotelName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
