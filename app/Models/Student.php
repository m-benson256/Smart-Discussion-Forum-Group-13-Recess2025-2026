<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $primaryKey = 'StudentID'; [cite: 16]
    public $incrementing = false; 
    protected $fillable = ['StudentID', 'Category', 'CategoryID']; [cite: 16]

    public function member() { return $this->belongsTo(Member::class, 'StudentID'); } [cite: 16]
    public function quizAttempts() { return $this->hasMany(QuizAttempt::class, 'StudentID'); } [cite: 33, 35]

}
