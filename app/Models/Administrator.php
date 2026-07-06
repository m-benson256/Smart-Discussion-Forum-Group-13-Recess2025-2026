<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    //
    protected $primaryKey = 'AdminID'; 
    public $incrementing = false;
    protected $fillable = ['AdminID', 'AdminType']; 

    public function user() { return $this->belongsTo(User::class, 'AdminID'); } 
    public function issuedWarnings() { return $this->hasMany(Warning::class, 'IssuedBy'); } 

}
