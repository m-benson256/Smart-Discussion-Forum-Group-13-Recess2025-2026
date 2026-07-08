<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warnings extends Model
{
    //
    protected $primaryKey = 'WarningID';

    protected $fillable = ['WarningNumber', 'UserID', 'IssuedBy', 'Deadline', 'Status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function admin()
    {
        return $this->belongsTo(Administrator::class, 'IssuedBy');
    }}
