<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'day', 'open_time', 'close_time', 'is_24h'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}