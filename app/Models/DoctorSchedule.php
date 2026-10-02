<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'day', 'start_time', 'end_time', 'is_off'];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}