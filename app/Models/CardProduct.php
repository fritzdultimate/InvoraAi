<?php

namespace App\Models;

use App\Enums\CardForm;
use App\Enums\CardNetwork;
use App\Enums\CardOrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardProduct extends Model
{
    public const DESIGNS = [
        'obsidian' => 'Obsidian',
        'sapphire' => 'Sapphire',
        'gold' => 'Gold',
        'platinum' => 'Platinum',
        'emerald' => 'Emerald',
        'violet' => 'Violet',
    ];

    public const LIMIT_PERIODS = [
        'daily' => 'per day',
        'monthly' => 'per month',
        'total' => 'total',
    ];

    protected $fillable = [
        'name', 'network', 'form', 'design', 'tagline', 'preorder_fee', 'spending_limit', 'limit_period',
        'features', 'delivery_estimate', 'stock', 'max_per_user', 'is_active', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'network' => CardNetwork::class,
        'form' => CardForm::class,
        'preorder_fee' => 'decimal:2',
        'spending_limit' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(CardOrder::class);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order')->orderBy('preorder_fee');
    }

    // orders that hold a slot: everything except cancelled and rejected
    public function claimedCount(): int
    {
        return $this->orders()
            ->whereNotIn('status', [CardOrderStatus::CANCELLED->value, CardOrderStatus::REJECTED->value])
            ->count();
    }

    public function slotsLeft(): ?int
    {
        return $this->stock === null ? null : max($this->stock - $this->claimedCount(), 0);
    }

    public function limitLabel(): string
    {
        return '$'.number_format($this->spending_limit).' '.(self::LIMIT_PERIODS[$this->limit_period] ?? '');
    }
}
