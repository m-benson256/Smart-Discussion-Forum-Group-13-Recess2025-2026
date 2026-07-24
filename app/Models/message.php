<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'topic_id',
        'user_id',
        'body',
         'attachment_path',   // NEW
         'attachment_name',   // NEW
         'attachment_mime',   // NEW
         'attachment_size',
    ];

    protected $appends = ['attachment_url'];

public function getAttachmentUrlAttribute(): ?string
{
    return $this->attachment_path ? \Storage::url($this->attachment_path) : null;
}

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

    public function likedBy(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'message_likes', 'message_id', 'user_id')
                 ->withTimestamps();
}

public function reactions(): HasMany
{
    return $this->hasMany(\App\Models\MessageReaction::class);
}
}
