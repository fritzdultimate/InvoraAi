<?php

namespace App\Models;

use App\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model {
    protected $fillable = [
        'user_id', 
        'amount', 
        'meta', 
        'currency', 
        'wallet_id', 
        'nowpayments_invoice_id',
        'actually_paid',
        'note',
        'status',
        'bonus',
        'bonus_expires_at',
        'received_at',
        'address',
        'reference',
        'updated_at',
        'created_at',
        'receipt_path',
        'receipt_uploaded_at',
        'narration',
        'confirmed_at',
        'source',
        'created_by',
    ];

    protected $casts = [
        'meta' => 'array',
        'status' => DepositStatus::class,
        'created_at' => 'datetime',
        'received_at' => 'datetime',
        'receipt_uploaded_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function markFinished(): self {
        $this->update([
            'confirmed_at' => now(),
            'received_at' => now(),
        ]);
        return $this->setStatus(DepositStatus::FINISHED);
    }

    public function cancel(): self {
        return $this->setStatus(DepositStatus::CANCELLED);
    }

    protected function setStatus(DepositStatus $status): self {
        if ($this->status->isFinal()) {
            throw new \LogicException('Cannot change a finalized deposit.');
        }

        $this->update(['status' => $status]);

        return $this;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // public function transactions() {
    //     return $this->morphMany(Transaction::class, 'related');
    // }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function referralBonuses() {
        return $this->hasMany(ReferralBonus::class);
    }

    public function isAdminCreated(): bool {
        return $this->source === 'admin';
    }

    // money that counts as a real deposit: paid through an invoice, or a deposit an admin created for the user.
    // silent admin top-ups never create a deposit, so they never land here
    public function scopeReal($query) {
        return $query->where('actually_paid', '>', 0)
            ->where(fn ($q) => $q->whereNotNull('nowpayments_invoice_id')->orWhere('source', 'admin'));
    }
}
