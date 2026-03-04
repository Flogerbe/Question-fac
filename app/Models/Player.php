<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = ['prenom', 'nom', 'ip_hash', 'browser_token', 'played_at'];

    protected $casts = ['played_at' => 'datetime'];

    public function getFullNameAttribute(): string
    {
        return $this->nom ? $this->prenom . ' ' . strtoupper($this->nom) : $this->prenom;
    }

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
        if (\strlen($token) < 16) { return false; }
        $hash = hash('sha256', $token . config('app.key'));
        return self::where('browser_token', $hash)->whereNotNull('played_at')->exists();
    }

    public static function hasAlreadyPlayed(string $ip, ?string $browserToken): bool
    {
        if (self::hasPlayedFromIp($ip)) { return true; }
        if ($browserToken && self::hasPlayedWithToken($browserToken)) { return true; }
        return false;
    }

    /**
     * Count completed quiz sessions for this IP/token.
     * If $todayOnly=true, only counts sessions started today.
     */
    public static function countPlays(string $ip, ?string $browserToken, bool $todayOnly = false): int
    {
        $ipHash = self::hashIp($ip);

        $playerQuery = self::where('ip_hash', $ipHash)->whereNotNull('played_at');
        if ($todayOnly) {
            $playerQuery->whereDate('played_at', today());
        }
        $playerIds = $playerQuery->pluck('id');

        if ($browserToken && \strlen($browserToken) >= 16) {
            $tokenHash = hash('sha256', $browserToken . config('app.key'));
            $tokenQuery = self::where('browser_token', $tokenHash)->whereNotNull('played_at');
            if ($todayOnly) {
                $tokenQuery->whereDate('played_at', today());
            }
            $playerIds = $playerIds->merge($tokenQuery->pluck('id'))->unique();
        }

        return GameSession::whereIn('player_id', $playerIds)
            ->where('completed', true)
            ->count();
    }
}
