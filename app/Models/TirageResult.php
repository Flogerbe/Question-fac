<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TirageResult extends Model
{
    protected $fillable = ['type', 'game_session_id', 'rang', 'drawn_at'];

    protected $casts = ['drawn_at' => 'datetime'];

    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}
