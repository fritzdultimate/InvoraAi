<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model {
    protected $fillable = [
        'name',
        'level',
        'required_volume',
        'direct_referrals_volume',
        'one_time_bonus',
        'bonus',
        'deposits',
        'direct_referrals',
    ];

    public function ranks() {
        return $this->hasMany(UserRank::class);
    }

    // public function percentages() {
    //     return $this->hasMany(PerformancePercentage::class);
    // }
}
