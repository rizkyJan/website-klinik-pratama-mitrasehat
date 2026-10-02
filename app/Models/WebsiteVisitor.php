<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_uuid',
        'first_seen_at',
        'last_seen_at',
        'total_page_views',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function dailyVisits(): HasMany
    {
        return $this->hasMany(WebsiteDailyVisit::class, 'visitor_id');
    }
}
