<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model {
    protected $fillable = [
        'user_id',
        'rank_id',
        'achieved_at'
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function rank() {
        return $this->belongsTo(Rank::class);
    }
}
