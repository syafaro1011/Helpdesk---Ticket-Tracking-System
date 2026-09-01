<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    // Catatan terhubung ke satu Tiket
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    // Catatan ditulis oleh satu User (Pelapor/Teknisi/Admin)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}