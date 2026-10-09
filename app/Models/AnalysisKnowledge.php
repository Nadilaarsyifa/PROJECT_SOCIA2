<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalysisKnowledge extends Model
{
    protected $table = 'analysis_knowledge';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = null;

    protected $fillable = [
        'id_analysis',
        'id_knowledge',
    ];

    public function aiAnalysis(): BelongsTo
    {
        return $this->belongsTo(AIAnalysis::class, 'id_analysis', 'id_analysis');
    }

    public function knowledgeBase(): BelongsTo
    {
        return $this->belongsTo(KnowledgeBase::class, 'id_knowledge', 'id_knowledge');
    }
}