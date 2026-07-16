<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'category_id',
        'title',
        'description',
        'start_time',
        'duration_minutes',
        'total_marks',
        'passing_score',
        'shuffle_questions',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'shuffle_questions' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'CategoryID');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function announcement(): HasOne
    {
        return $this->hasOne(Announcements::class, 'QuizID', 'id');
    }
}
