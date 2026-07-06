<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warnings extends Model
{
    //
    protected $primaryKey = 'WarningID'; [cite: 27]
    protected $fillable = ['WarningNumber', 'UserID', 'IssuedBy', 'Deadline', 'Status']; [cite: 27]

    public function user() { return $this->belongsTo(User::class, 'UserID'); } [cite: 27, 35]
    public function admin() { return $this->belongsTo(Administrator::class, 'IssuedBy'); } [cite: 27, 35]

}
