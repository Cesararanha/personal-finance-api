<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $transactionData,
        public readonly string $userName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova transação registrada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction_created',
        );
    }
}
