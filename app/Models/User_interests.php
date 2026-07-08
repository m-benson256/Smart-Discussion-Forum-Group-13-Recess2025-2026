<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User_interests extends Model
{
    //
    protected $primaryKey = 'InterestID';

    protected $fillable = ['InterestName'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'member_user_interests',
            'InterestID',
            'UserID'
        )->withTimestamps();
    }
}
