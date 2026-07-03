<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmissionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'quiz_id',
        'student_id',
        'submission_type',
        'score_before_submission',
        'answered_questions',
        'total_questions',
        'submitted_at',
        'notes'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score_before_submission' => 'decimal:2'
    ];

    // Relationships
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeAutoSubmitted($query)
    {
        return $query->where('submission_type', 'auto_time_expired');
    }

    public function scopeManualSubmitted($query)
    {
        return $query->where('submission_type', 'manual');
    }
}