<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    //
    protected $primaryKey = 'ID'; [cite: 28]
    protected $fillable = ['Title', 'QuizID']; [cite: 28]

    public function quiz() { return $this->belongsTo(Quiz::class, 'QuizID'); } [cite: 28, 35]

}
