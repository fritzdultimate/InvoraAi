<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RankBonus extends Model {
    protected $fillable = [
        'user_id',
        'rank_id',
        'amount',
        'title',
        'description',
        'status',
        'credited_at',
        'locked_at',
    ];

    protected $cast = [
        'credited_at' => 'datetime',
        'locked_at' => 'datetime'
    ];
}
