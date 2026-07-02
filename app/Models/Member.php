<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    //
    protected $primaryKey = 'UserID'; [cite: 14]
    protected $fillable = ['Name', 'Email', 'Password', 'Status']; [cite: 14]
    protected $hidden = ['Password'];

    // Subclass Profile links
    public function student() { return $this->hasOne(Student::class, 'StudentID'); } [cite: 16]
    public function lecturer() { return $this->hasOne(Lecturer::class, 'LecturerID'); } [cite: 17]
    public function administrator() { return $this->hasOne(Administrator::class, 'AdminID'); } [cite: 18, 19]

    // Relationships [cite: 35]
    public function messages() { return $this->hasMany(Message::class, 'UserID'); } [cite: 35]
    public function topics() { return $this->hasMany(Topic::class, 'UserID'); } [cite: 35]
    public function groups() { return $this->belongsToMany(Group::class, 'group_member', 'member_id', 'group_id'); } [cite: 35]
    public function warnings() { return $this->hasMany(Warning::class, 'UserID'); } [cite: 35]
}
