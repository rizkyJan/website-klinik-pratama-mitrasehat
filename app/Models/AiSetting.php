<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function valueOf(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('key', $key)->value('value');
        return $value === null ? $default : $value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::valueOf($key, $default ? '1' : '0');
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );
    }
}
