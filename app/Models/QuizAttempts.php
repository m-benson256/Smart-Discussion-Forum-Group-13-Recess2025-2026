<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempts extends Model
{
    //
    protected $table = 'quiz_attempts';
    protected $primaryKey = 'ID'; [cite: 33]
    protected $fillable = ['Score', 'SubmissionTime', 'StudentID', 'QuizID', 'AutoSubmitted']; [cite: 33]

    public function student() { return $this->belongsTo(Student::class, 'StudentID'); } [cite: 33, 35]
    public function quiz() { return $this->belongsTo(Quiz::class, 'QuizID'); } [cite: 33, 35]

}
