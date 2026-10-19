<?php

namespace App\Mail;

use App\Enums\LedgerAsset;
use App\Models\CardOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// One email for every card pre-order update; the headline and text change per step.
class CardOrderUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CardOrder $order,
        public string $headline,
        public string $text,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->headline.' · '.$this->order->reference);
    }

    public function content(): Content
    {
        $rows = [
            'Reference' => $this->order->reference,
            'Card' => $this->order->card_name.' · '.$this->order->network->label(),
            'Status' => $this->order->status->label(),
            'Pre-order fee' => '$'.number_format($this->order->fee, 2),
            'Paid from' => LedgerAsset::from($this->order->paid_from)->label(),
        ];

        if ($this->order->tracking_number) {
            $rows['Tracking'] = trim($this->order->courier.' '.$this->order->tracking_number);
        }

        if ($this->order->last_four) {
            $rows['Card number'] = '•••• '.$this->order->last_four;
        }

        return new Content(
            view: 'emails.cards.update',
            with: [
                'order' => $this->order,
                'headline' => $this->headline,
                'text' => $this->text,
                'rows' => $rows,
                'url' => route('cards.order', $this->order),
            ],
        );
    }
}
