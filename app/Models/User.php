<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'account_id';

    protected $fillable = [
        'account_id',
        'full_name',
        'phone',
        'gender',
        'birthdate',
        'avatar',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }
}
