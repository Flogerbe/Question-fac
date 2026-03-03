<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JerseyVote extends Model
{
    protected $fillable = ['jersey_option_id', 'ip_hash'];

    public function jerseyOption(): BelongsTo
    {
        return $this->belongsTo(JerseyOption::class);
    }
}
