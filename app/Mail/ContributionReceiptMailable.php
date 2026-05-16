<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContributionReceiptMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $contribution;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Contribution $contribution, $pdfContent)
    {
        $this->contribution = $contribution;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Official Receipt: ' . $this->contribution->receipt_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contribution_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, "Receipt_{$this->contribution->receipt_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
