<?php

namespace App\Mail;

use App\Models\BotInvestment;
use App\Models\RoiPromo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoiBoostRewardMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BotInvestment $investment,
        public RoiPromo $promo,
        public string $profit,
        public string $extra,
        public bool $isFirst = false,
    ) {}

    public function envelope(): Envelope {
        return new Envelope(
            subject: $this->isFirst
                ? 'Your ' . $this->promo->multiplierLabel() . ' ROI boost is paying out'
                : 'Boosted payout credited — ' . $this->investment->bot->name,
        );
    }

    public function content(): Content {
        return new Content(
            view: 'emails.promo.roi-boost-reward',
            with: [
                'investment' => $this->investment,
                'promo' => $this->promo,
                'profit' => $this->profit,
                'extra' => $this->extra,
                'isFirst' => $this->isFirst,
                'url' => route('investments.item', ['id' => $this->investment->id]),
            ]
        );
    }
}
