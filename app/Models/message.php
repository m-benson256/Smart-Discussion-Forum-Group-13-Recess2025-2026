<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    //
    protected $primaryKey = 'PostID'; 
    protected $fillable = ['Content', 'TopicID', 'UserID']; 

    public function member() { return $this->belongsTo(Member::class, 'UserID'); } [cite: 35]
    public function topic() { return $this->belongsTo(Topic::class, 'TopicID'); } [cite: 35]

}
