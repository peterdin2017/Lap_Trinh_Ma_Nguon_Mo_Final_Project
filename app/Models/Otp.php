<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $primaryKey = 'otp_id';
    public $timestamps = false;
    protected $fillable = ['account_id', 'otp_code', 'attempts', 'purpose', 'expires_at', 'is_used'];
}

