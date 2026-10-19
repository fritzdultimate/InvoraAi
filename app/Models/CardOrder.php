<?php

namespace App\Models;

use App\Enums\CardForm;
use App\Enums\CardNetwork;
use App\Enums\CardOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CardOrder extends Model
{
    protected $fillable = [
        'reference', 'user_id', 'card_product_id', 'card_name', 'network', 'form', 'design', 'fee',
        'spending_limit', 'limit_period', 'paid_from', 'status', 'name_on_card', 'phone', 'address_line1',
        'address_line2', 'city', 'state', 'postal_code', 'country', 'courier', 'tracking_number',
        'tracking_url', 'last_four', 'expiry', 'user_message', 'admin_note', 'debit_ledger_id',
        'refund_ledger_id', 'approved_at', 'processing_at', 'shipped_at', 'activated_at', 'cancelled_at',
        'rejected_at', 'refunded_at',
    ];

    protected $casts = [
        'network' => CardNetwork::class,
        'form' => CardForm::class,
        'status' => CardOrderStatus::class,
        'fee' => 'decimal:2',
        'spending_limit' => 'decimal:2',
        'approved_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipped_at' => 'datetime',
        'activated_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rejected_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(CardProduct::class, 'card_product_id');
    }

    public static function newReference(): string
    {
        do {
            $reference = 'CRD-'.Str::upper(Str::random(8));
        } while (static::where('reference', $reference)->exists());

        return $reference;
    }

    public function isPhysical(): bool
    {
        return $this->form === CardForm::PHYSICAL;
    }

    // when each step of the journey happened, for the timeline
    public function stepTime(CardOrderStatus $step): ?\Illuminate\Support\Carbon
    {
        return match ($step) {
            CardOrderStatus::PENDING => $this->created_at,
            CardOrderStatus::APPROVED => $this->approved_at,
            CardOrderStatus::PROCESSING => $this->processing_at,
            CardOrderStatus::SHIPPED => $this->shipped_at,
            CardOrderStatus::ACTIVE => $this->activated_at,
            CardOrderStatus::CANCELLED => $this->cancelled_at,
            CardOrderStatus::REJECTED => $this->rejected_at,
        };
    }

    public function shippingAddress(): string
    {
        return collect([$this->address_line1, $this->address_line2, $this->city, $this->state, $this->postal_code, $this->country])
            ->filter()
            ->implode(', ');
    }

    public function limitLabel(): string
    {
        return '$'.number_format($this->spending_limit).' '.(CardProduct::LIMIT_PERIODS[$this->limit_period] ?? '');
    }
}
