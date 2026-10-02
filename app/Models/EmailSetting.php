<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'sender_name',
        'email',
        'google_id',
        'smtp_host',
        'smtp_port',
        'smtp_scheme',
        'smtp_username',
        'smtp_password',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'google_scopes',
        'connected_at',
        'last_tested_at',
        'last_error',
        'configured_by',
    ];

    protected $casts = [
        'smtp_password' => 'encrypted',
        'google_access_token' => 'encrypted',
        'google_refresh_token' => 'encrypted',
        'google_token_expires_at' => 'datetime',
        'connected_at' => 'datetime',
        'last_tested_at' => 'datetime',
    ];

    public function configuredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'configured_by');
    }

    public function isConnected(): bool
    {
        return $this->provider === 'google'
            && filled($this->email)
            && filled($this->google_refresh_token)
            && $this->connected_at !== null
            && blank($this->last_error);
    }

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
