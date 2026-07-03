<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'points',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Get correct option
    public function getCorrectOptionAttribute()
    {
        return $this->options()->where('is_correct', true)->first();
    }

    // Get all correct options (for multiple correct answers)
    public function getCorrectOptionsAttribute()
    {
        return $this->options()->where('is_correct', true)->get();
    }
}