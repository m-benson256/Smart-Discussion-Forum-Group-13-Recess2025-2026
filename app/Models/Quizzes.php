<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    //
    protected $primaryKey = 'QuizID'; [cite: 31]
    protected $fillable = ['Duration', 'Topic', 'StartTime', 'LecturerID']; [cite: 31]

    public function lecturer() { return $this->belongsTo(Lecturer::class, 'LecturerID'); } [cite: 31, 35]
    public function attempts() { return $this->hasMany(QuizAttempt::class, 'QuizID'); } [cite: 33, 35]
    public function announcement() { return $this->hasOne(Announcement::class, 'QuizID'); } [cite: 28, 35]

}
