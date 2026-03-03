<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['question', 'coach_hint', 'ordre', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function correctAnswer()
    {
        return $this->answers()->where('is_correct', true)->first();
    }

    public function gameAnswers(): HasMany
    {
        return $this->hasMany(GameAnswer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('ordre');
    }
}
