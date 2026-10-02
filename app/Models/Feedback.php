<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';
    protected $fillable = [
        'name',
        'email',
        'type',
        'subject',
        'message',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(FeedbackReply::class)->orderBy('sent_at');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'kritik' => 'Kritik',
            'saran' => 'Saran',
            'apresiasi' => 'Apresiasi',
            default => 'Lainnya',
        };
    }
}
