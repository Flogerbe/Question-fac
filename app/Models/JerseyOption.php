<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JerseyOption extends Model
{
    protected $fillable = ['nom', 'description', 'image_path', 'couleurs', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function votes(): HasMany
    {
        return $this->hasMany(JerseyVote::class);
    }

    public function votesCount(): int
    {
        return $this->votes()->count();
    }
}
