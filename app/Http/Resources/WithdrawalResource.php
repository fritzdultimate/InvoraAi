<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fee = (float) ($this->meta['fee'] ?? 0);

        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => (float) $this->amount,
            'fee' => $fee,
            'net_amount' => (float) $this->amount - $fee,
            'currency' => $this->currency?->code,
            'network' => $this->network?->name,
            'address' => $this->address,
            'status' => $this->status?->value,
            'tx_hash' => $this->tx_hash,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
