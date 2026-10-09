<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AIAnalysis extends Model
{
    protected $table = 'ai_analyses';

    protected $primaryKey = 'id_analysis';

    protected $fillable = [
        'summary',
        'attention_point',
        'no_ticket',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'no_ticket', 'no_ticket');
    }

    public function actionChecklists(): HasMany
    {
        return $this->hasMany(ActionChecklist::class, 'id_analysis', 'id_analysis');
    }

    public function knowledgeBases(): BelongsToMany
    {
        return $this->belongsToMany(
            KnowledgeBase::class,
            'analysis_knowledge',
            'id_analysis',
            'id_knowledge',
            'id_analysis',
            'id_knowledge'
        );
    }
}