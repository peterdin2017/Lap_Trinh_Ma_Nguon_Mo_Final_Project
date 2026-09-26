<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $fillable = ['account_id', 'full_name', 'phone'];
}
