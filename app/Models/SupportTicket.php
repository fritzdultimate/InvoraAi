<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'type',
        'priority',
        'status',
        'description',
        'closed_at',
        'ticket_number'
    ];
    protected $casts = ['meta'=>'array'];

    public function messages() {
        return $this->hasMany(SupportTicketMessage::class)->latest();
    }

    public function staffReplies() {
        return $this->messages()->where('is_staff', true);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
