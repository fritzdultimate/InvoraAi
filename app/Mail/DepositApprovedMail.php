<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $trx;
    public $method;
    public $date;
    public $bonus;
    public $url;
    public $requestedAmount;

    /**
     * Create a new message instance.
     *
     * @param float|string $amount Amount actually credited to the user.
     * @param string $trx Deposit reference.
     * @param string $method Currency/payment method.
     * @param string $date Formatted confirmation date.
     * @param string $url Dashboard URL for the CTA button.
     * @param float|string|null $bonus Deposit bonus credited alongside this deposit, if any.
     * @param float|string|null $requestedAmount When set and different from $amount, the
     *        email is rendered in "partial payment" mode showing both the requested and
     *        the actually-received amount.
     */
    public function __construct($amount, $trx, $method, $date, $url, $bonus = null, $requestedAmount = null) {
        $this->amount = $amount;
        $this->trx = $trx;
        $this->method = $method;
        $this->date = $date;
        $this->bonus = $bonus;
        $this->url = $url;
        $this->requestedAmount = $requestedAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isPartial = $this->requestedAmount !== null && (float) $this->requestedAmount > (float) $this->amount;

        return new Envelope(
            subject: $isPartial ? 'Partial Deposit Received ⚠️' : 'Deposit Confirmed ✅',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit.confirmed',
            with: [
                'amount' => $this->amount,
                'trx' => $this->trx,
                'method' => $this->method,
                'date' => $this->date,
                'bonus' => $this->bonus,
                'url' => $this->url,
                'requestedAmount' => $this->requestedAmount,
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
