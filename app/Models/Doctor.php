<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialization', 'photo', 'is_active', 'sort_order'];

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}