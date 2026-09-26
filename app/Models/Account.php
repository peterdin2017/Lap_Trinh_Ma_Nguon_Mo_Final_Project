<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $primaryKey = 'account_id';
    public $timestamps = false;
    protected $fillable = ['email','password_hash', 'role', 'status', 'is_verified'];
}
