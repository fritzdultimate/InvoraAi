<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $meta = $this->meta ?? [];

        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => (float) $this->amount,
            'status' => $this->status?->value,
            'pay_currency' => $meta['pay_currency'] ?? $this->currency,
            'pay_amount' => (float) ($meta['pay_amount'] ?? 0),
            'pay_address' => $this->address,
            'network' => strtoupper((string) $this->currency),
            'expires_at' => $this->created_at?->copy()->addMinutes(20)->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
