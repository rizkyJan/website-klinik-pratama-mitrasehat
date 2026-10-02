<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'content',
        'start_date',
        'end_date',
        'is_active',
        'is_pinned',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        $today = now('Asia/Jakarta')->toDateString();

        return $query
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('start_date')
            ->orderByDesc('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'important' => 'Penting',
            'urgent' => 'Darurat',
            default => 'Informasi',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) {
            return 'Nonaktif';
        }

        $today = now('Asia/Jakarta')->toDateString();

        if ($this->start_date && $this->start_date->toDateString() > $today) {
            return 'Terjadwal';
        }

        if ($this->end_date && $this->end_date->toDateString() < $today) {
            return 'Berakhir';
        }

        return 'Aktif';
    }

    public function isCurrentlyVisible(): bool
    {
        return $this->status_label === 'Aktif';
    }
}
