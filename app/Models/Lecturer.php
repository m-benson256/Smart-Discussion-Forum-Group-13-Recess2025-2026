<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    protected $fillable = ['user_id', 'contact', 'DegreeType'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quizzes::class, 'LecturerID', 'id');
    }
}