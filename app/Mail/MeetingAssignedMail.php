<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $link;
    public $messageText;
    public $start;
    public $end;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$link,$messageText,$start,$end)
    {
        $this->user = $user;
        $this->link = $link;
        $this->messageText = $messageText;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Task Meeting Invitation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mail.meeting-mail',
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
