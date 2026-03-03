<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = ['prenom', 'ip_hash', 'browser_token', 'played_at'];

    protected $casts = ['played_at' => 'datetime'];

    public function gameSessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    public static function hashIp(string $ip): string
    {
        return hash('sha256', $ip . config('app.key'));
    }

    public static function hasPlayedFromIp(string $ip): bool
    {
        $hash = self::hashIp($ip);
        return self::where('ip_hash', $hash)->whereNotNull('played_at')->exists();
    }

    public static function hasPlayedWithToken(string $token): bool
    {
        if (strlen($token) < 16) return false;
        $hash = hash('sha256', $token . config('app.key'));
        return self::where('browser_token', $hash)->whereNotNull('played_at')->exists();
    }

    public static function hasAlreadyPlayed(string $ip, ?string $browserToken): bool
    {
        if (self::hasPlayedFromIp($ip)) return true;
        if ($browserToken && self::hasPlayedWithToken($browserToken)) return true;
        return false;
    }
}
