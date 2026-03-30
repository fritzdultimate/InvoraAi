<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnilevelPercentage extends Model {
    protected $fillable = [
        'level',
        'percentage',
    ];
}
