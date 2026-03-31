<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $trx;
    public $method;
    public $date;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($amount, $trx, $method, $date, $url) {
        $this->amount = $amount;
        $this->trx = $trx;
        $this->method = $method;
        $this->date = $date;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Confirmed ✅',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit.expired',
            with: [
                'amount' => $this->amount,
                'trx' => $this->trx,
                'method' => $this->method,
                'date' => $this->date,
                'url' => $this->url,
            ]
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
