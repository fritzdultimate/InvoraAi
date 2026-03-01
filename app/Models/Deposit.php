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
        'amount_paid',
        'note',
        'status',
        'bonus',
        'bonus_expires_at',
        'processed_at',
        'address',
        'reference',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'status' => DepositStatus::class,
        'created_at' => 'datetime'
    ];

    public function markFinished(): self {
        $this->update([
            'confirmed_at' => now(),
            'processed_at' => now(),
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
}
