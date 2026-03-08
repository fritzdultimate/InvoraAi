<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class KycVerification extends Model {
    protected $fillable = [
        'user_id',
        'country',
        'date_of_birth',
        'document_type',
        'document_front',
        'document_back',
        'address',
        'status',
        'admin_note',
        'reviewed_at',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
