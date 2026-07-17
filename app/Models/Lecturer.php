<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
<<<<<<< HEAD
    protected $fillable = ['user_id', 'contact', 'DegreeType'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
=======
    //
    protected $primaryKey = 'LecturerID'; 
    public $incrementing = false;
    protected $fillable = ['LecturerID', 'Department', 'DegreeType']; 

    public function member() { return $this->belongsTo(Member::class, 'LecturerID'); } 
    public function quizzes() { return $this->hasMany(Quiz::class, 'LecturerID'); } 
>>>>>>> a71614295232ad323805f7255ab2b0c2a15bcebf

}
