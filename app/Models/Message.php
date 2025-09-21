<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'segment',
        'phone',
        'name',
        'content',
        'status',
        'external_message_id',
        'sent_at',
        'response_payload',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'response_payload' => 'array',
    ];
    // Status filtre
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
    // Segment filtre
    public function scopeSegment($query, string $segment)
    {
        return $query->where('segment', $segment);
    }

    // Telefon filtre
    public function scopePhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }
}
