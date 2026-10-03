<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /** $kind is "week" (about 7 days before the trip) or "day" (the day before). */
    public function __construct(public Booking $booking, public string $kind = 'week')
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->kind === 'day'
            ? 'Your trip is tomorrow / Tripmu besok'
            : 'Your trip is coming up / Tripmu sebentar lagi';

        return new Envelope(subject: $subject . ' — Overlander #' . $this->booking->id);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-reminder');
    }
}
