<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketAttachment extends Model {
    protected $guarded = [];

    public function message() {
        return $this->belongsTo(SupportTicketMessage::class, 'support_ticket_message_id');
    }
}
