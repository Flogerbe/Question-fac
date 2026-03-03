<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    protected $fillable = [
        'player_id', 'score', 'temps_total',
        'joker_fifty', 'joker_public', 'joker_coach',
        'completed', 'counted',
    ];

    protected $casts = [
        'joker_fifty' => 'boolean',
        'joker_public' => 'boolean',
        'joker_coach' => 'boolean',
        'completed' => 'boolean',
        'counted' => 'boolean',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function gameAnswers(): HasMany
    {
        return $this->hasMany(GameAnswer::class);
    }
}
