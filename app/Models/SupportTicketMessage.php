<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model {
    protected $guarded = [];
    protected $casts = ['meta'=>'array'];

    public function ticket() {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
