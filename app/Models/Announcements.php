<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcements extends Model
{
    protected $primaryKey = 'ID';
    protected $fillable = ['Title', 'QuizID'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'QuizID', 'id');
    }

}
