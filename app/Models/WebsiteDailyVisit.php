<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteDailyVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'visit_date',
        'first_seen_at',
        'last_seen_at',
        'page_views',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(WebsiteVisitor::class, 'visitor_id');
    }
}
