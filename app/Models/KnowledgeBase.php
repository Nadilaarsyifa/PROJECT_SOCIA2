<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_bases';

    protected $primaryKey = 'id_knowledge';

    protected $fillable = [
        'title',
        'content',
        'source',
        'category',
    ];

    public function aiAnalyses(): BelongsToMany
    {
        return $this->belongsToMany(
            AIAnalysis::class,
            'analysis_knowledge',
            'id_knowledge',
            'id_analysis',
            'id_knowledge',
            'id_analysis'
        );
    }
}