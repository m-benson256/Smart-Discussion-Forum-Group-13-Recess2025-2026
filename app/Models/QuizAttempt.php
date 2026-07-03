<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'start_time',
        'end_time',
        'score',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'score' => 'decimal:2'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Check if attempt is still within time
    public function isWithinTime()
    {
        if ($this->status === 'submitted') {
            return false;
        }
        
        $timeElapsed = now()->diffInMinutes($this->start_time);
        return $timeElapsed < $this->quiz->duration_minutes;
    }

    // Get remaining seconds
    public function getRemainingSecondsAttribute()
    {
        if ($this->status === 'submitted') {
            return 0;
        }
        
        $timeElapsed = now()->diffInMinutes($this->start_time);
        $remaining = ($this->quiz->duration_minutes - $timeElapsed) * 60;
        return max(0, $remaining);
    }

    // Calculate score
    public function calculateScore()
    {
        $totalQuestions = $this->quiz->questions->count();
        if ($totalQuestions === 0) {
            return 0;
        }
        
        $correctAnswers = $this->answers()->where('is_correct', true)->count();
        return ($correctAnswers / $totalQuestions) * 100;
    }
}