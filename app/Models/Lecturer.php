<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    //
    protected $primaryKey = 'LecturerID'; [cite: 17]
    public $incrementing = false;
    protected $fillable = ['LecturerID', 'Department', 'DegreeType']; [cite: 17]

    public function member() { return $this->belongsTo(Member::class, 'LecturerID'); } [cite: 17]
    public function quizzes() { return $this->hasMany(Quiz::class, 'LecturerID'); } [cite: 31, 35]

}
