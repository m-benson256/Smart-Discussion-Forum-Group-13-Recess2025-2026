<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicView extends Model
{
    protected $fillable = [
        'user_id',
        'topic_id',
        'view_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}