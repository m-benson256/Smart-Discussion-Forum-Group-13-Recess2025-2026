<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topic_id',
        'user_id',
        'body',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flaggedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_flags', 'message_id', 'user_id')
                     ->withTimestamps();
    }
}