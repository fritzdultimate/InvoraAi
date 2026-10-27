<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isCredit = $this->credit > 0;

        return [
            'id' => $this->id,
            'type' => $isCredit ? 'credit' : 'debit',
            'amount' => (float) ($isCredit ? $this->credit : $this->debit),
            'asset' => $this->asset?->value,
            'asset_label' => $this->asset?->label(),
            'reference_type' => $this->reference_type?->value,
            'label' => Str::headline($this->reference_type?->value ?? 'transaction'),
            'description' => $this->description,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
