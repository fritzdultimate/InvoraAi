<?php

namespace App\Models;

use App\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model {
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'status' => DepositStatus::class,
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

    // public function transactions() {
    //     return $this->morphMany(Transaction::class, 'related');
    // }
}
