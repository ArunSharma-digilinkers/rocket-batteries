<?php

namespace App\Mail;

use App\Models\Warranty;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WarrantyReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Warranty $warranty)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Warranty Registration — '.$this->warranty->serial_no);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.warranty-received');
    }
}
