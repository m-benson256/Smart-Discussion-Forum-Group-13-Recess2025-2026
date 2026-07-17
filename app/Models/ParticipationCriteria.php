<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipationCriteria extends Model
{

    protected $table = 'participation_criteria';

    protected $fillable = [
        'lecturer_id',
        'points_per_message',
        'points_per_reaction_given',
        'max_score',
    ];

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}