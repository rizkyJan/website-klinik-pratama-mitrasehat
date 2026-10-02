<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthPackage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'items', 'is_active', 'sort_order'];

    protected $casts = ['items' => 'array'];
}