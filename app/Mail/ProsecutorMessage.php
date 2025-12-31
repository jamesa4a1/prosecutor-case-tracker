<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProsecutorMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $senderEmail;
    public $messageContent;

    /**
     * Create a new message instance.
     */
    public function __construct($senderName, $senderEmail, $messageContent)
    {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->messageContent = $messageContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Case Update from ' . $this->senderName,
            replyTo: [$this->senderEmail],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.prosecutor-message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
