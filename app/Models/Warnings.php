<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warnings extends Model
{
    protected $fillable = ['user_id', 'warning_number', 'reason', 'issued_at', 'expires_at', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
<<<<<<< HEAD
}

    
=======
}
>>>>>>> a71614295232ad323805f7255ab2b0c2a15bcebf
