<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    //
    protected $primaryKey = 'LecturerID'; 
    public $incrementing = false;
    protected $fillable = ['LecturerID', 'Department', 'DegreeType']; 

    public function member() { return $this->belongsTo(Member::class, 'LecturerID'); } 
    public function quizzes() { return $this->hasMany(Quiz::class, 'LecturerID'); } 

}
