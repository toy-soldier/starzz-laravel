<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name',
        'date_of_birth',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}