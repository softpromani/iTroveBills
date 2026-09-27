<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfContent;
    public $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $pdfContent = null, $pdfFilename = 'Invoice.pdf')
    {
        $this->data = $mailData;
        $this->pdfContent = $pdfContent;
        $this->pdfFilename = $pdfFilename;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->data['subject'] ?? ('Your Bill From ' . ($this->data['Seller_Company'] ?? 'ITrove Bills'));
        return new Envelope(
            subject: $subject,
            replyTo: $this->data['email'] ?? null,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.bill',
            with: ['data' => $this->data],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdfContent) {
            return [
                Attachment::fromData(fn () => $this->pdfContent, $this->pdfFilename)
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
