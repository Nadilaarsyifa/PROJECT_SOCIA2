<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionChecklist extends Model
{
    protected $table = 'action_checklists';

    protected $primaryKey = 'id_action';

    protected $fillable = [
        'action',
        'status',
        'notes',
        'action_order',
        'id_analysis',
    ];

    protected function casts(): array
    {
        return [
            'action_order' => 'integer',
        ];
    }

    public function aiAnalysis(): BelongsTo
    {
        return $this->belongsTo(AIAnalysis::class, 'id_analysis', 'id_analysis');
    }
}