<?php

namespace App\Models;

class Admin extends User
{
    protected $table = 'users';

    public function getMorphClass(): string
    {
        return User::class;
    }

    public function guardName(): string
    {
        return 'web';
    }
}
