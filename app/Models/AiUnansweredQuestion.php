<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUnansweredQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'last_question',
        'normalized_question',
        'occurrences',
        'status',
        'knowledge_id',
        'first_asked_at',
        'last_asked_at',
    ];

    protected $casts = [
        'first_asked_at' => 'datetime',
        'last_asked_at' => 'datetime',
    ];

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(AiKnowledge::class, 'knowledge_id');
    }
}
