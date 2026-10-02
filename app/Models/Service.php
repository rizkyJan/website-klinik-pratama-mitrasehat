<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'detail', 'icon', 'is_active', 'sort_order'];

    public function schedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}