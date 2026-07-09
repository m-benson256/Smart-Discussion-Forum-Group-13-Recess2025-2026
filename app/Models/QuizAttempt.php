<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'attempt_id');
    }

    public function deadline(): ?\Carbon\Carbon
{
    // Anchor to the quiz's scheduled start_time if one is set.
    // Fall back to the attempt's own started_at for quizzes with no fixed schedule.
    $anchor = $this->quiz->start_time ?: $this->started_at;

    return $anchor
        ? $anchor->copy()->addMinutes($this->quiz->duration_minutes)
        : null;
}
}
