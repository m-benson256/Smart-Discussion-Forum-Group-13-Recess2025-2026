<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_interests extends Model
{
    //
     protected $primaryKey = 'InterestID';

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            'member_user_interest',
            'InterestID',
            'UserID'
        )->withTimestamps();
    }
}
