<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public float $amount,
        public string $reference,
        public string $wallet,
        public string $date,
        public string $url,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Withdrawal Request Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawal.request',
            with: [
                'amount' => $this->amount,
                'reference' => $this->reference,
                'wallet' => $this->wallet,
                'date' => $this->date,
                'url' => $this->url,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
