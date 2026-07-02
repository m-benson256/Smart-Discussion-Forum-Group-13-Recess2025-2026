<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    //
    protected $primaryKey = 'UserID'; 
    protected $fillable = ['Name', 'Email', 'Password', 'Status']; 
    protected $hidden = ['Password'];

    // Subclass Profile links
    public function student() { return $this->hasOne(Student::class, 'StudentID'); } 
    public function lecturer() { return $this->hasOne(Lecturer::class, 'LecturerID'); } 
    public function administrator() { return $this->hasOne(Administrator::class, 'AdminID'); } 

    // Relationships 
    public function messages() { return $this->hasMany(Message::class, 'UserID'); } 
    public function topics() { return $this->hasMany(Topic::class, 'UserID'); } 
    public function groups() { return $this->belongsToMany(Group::class, 'group_member', 'member_id', 'group_id'); } 
    public function warnings() { return $this->hasMany(Warning::class, 'UserID'); } 

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(
            UserInterest::class,
            'member_user_interest',
            'UserID',
            'InterestID'
        )->withTimestamps();
    }
}
