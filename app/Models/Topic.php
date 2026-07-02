<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $primaryKey = 'TopicID'; [cite: 25]
    protected $fillable = ['Title', 'Category', 'GroupID', 'UserID']; [cite: 25]

    public function creator() { return $this->belongsTo(Member::class, 'UserID'); } [cite: 35]
    public function group() { return $this->belongsTo(Group::class, 'GroupID'); } [cite: 35]
    public function messages() { return $this->hasMany(Message::class, 'TopicID'); } [cite: 35]

}
