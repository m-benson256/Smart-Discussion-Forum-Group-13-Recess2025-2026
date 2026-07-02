<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    //
    protected $primaryKey = 'AdminID'; [cite: 19]
    public $incrementing = false;
    protected $fillable = ['AdminID', 'AdminType']; [cite: 19]

    public function member() { return $this->belongsTo(Member::class, 'AdminID'); } [cite: 19]
    public function issuedWarnings() { return $this->hasMany(Warning::class, 'IssuedBy'); } [cite: 27, 35]

}
