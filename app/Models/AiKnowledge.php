<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledge extends Model
{
    use HasFactory;

    protected $table = 'ai_knowledge';

    protected $fillable = [
        'title',
        'category',
        'content',
        'keywords',
        'source',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
