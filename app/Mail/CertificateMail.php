<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $course, $certificatePath;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $course, $certificatePath)
    {
        $this->user = $user;
        $this->course = $course;
        $this->certificatePath = $certificatePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Certificate Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.certificate',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Attach the certificate file
        return [
            Attachment::fromStoragePath($this->certificatePath)
                ->as('certificate.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
