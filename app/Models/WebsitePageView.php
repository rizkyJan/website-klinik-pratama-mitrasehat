<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_date',
        'path',
        'page_name',
        'views',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
