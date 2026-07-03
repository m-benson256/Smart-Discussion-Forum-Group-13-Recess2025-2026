<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $primaryKey = 'StudentID'; 
    public $incrementing = false; 
    //protected $fillable = ['StudentID', 'Category', 'CategoryID']; 
protected $fillable = [
    'name',
    'email',
    'password',
    'student_id',
    'department',
    'year_of_study',
    'is_active',
    'warning_count',
    'is_blacklisted',
    'blacklisted_until',
    'last_activity_at',
];
    public function member() { return $this->belongsTo(Member::class, 'StudentID'); } 
    public function quizAttempts() { return $this->hasMany(QuizAttempt::class, 'StudentID'); }

}
