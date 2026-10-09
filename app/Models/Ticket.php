<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $primaryKey = 'no_ticket';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'no_ticket',
        'offense_id',
        'detection',
        'risk_level',
        'event_time',
        'status',
        'raw_message',
        'inserted_at',
        'id_user',
    ];

    protected function casts(): array
    {
        return [
            'event_time' => 'datetime',
            'inserted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AIAnalysis::class, 'no_ticket', 'no_ticket');
    }
}