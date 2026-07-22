<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $newPassword, $subject = null)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
        $this->subject = $subject ?? 'Your Account Password Has Been Reset - TmcsSmart';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
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
