<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Generic admin notification email — one reusable template instead of a
 * near-identical Mailable per event type. Used for deposit-approved,
 * withdrawal-requested, withdrawal-approved and withdrawal-rejected alerts;
 * send more via AdminNotifier::notify(new AdminAlertMail(...)).
 */
class AdminAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $subjectLine Email subject line.
     * @param string $badge Short uppercase label shown in the pill badge, e.g. "DEPOSIT APPROVED".
     * @param string $badgeColor One of: success, warning, danger, info.
     * @param string $heading Headline sentence.
     * @param string $intro Short supporting sentence.
     * @param array<string, string> $rows Label => value pairs shown as a details table.
     * @param string|null $url Optional CTA link into the admin panel.
     * @param string|null $ctaLabel Optional CTA button label (defaults to "View in Admin").
     */
    public function __construct(
        public string $subjectLine,
        public string $badge,
        public string $badgeColor,
        public string $heading,
        public string $intro,
        public array $rows,
        public ?string $url = null,
        public ?string $ctaLabel = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.alert',
            with: [
                'badge' => $this->badge,
                'badgeColor' => $this->badgeColor,
                'heading' => $this->heading,
                'intro' => $this->intro,
                'rows' => $this->rows,
                'url' => $this->url,
                'ctaLabel' => $this->ctaLabel,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
