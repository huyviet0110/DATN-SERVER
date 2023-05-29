<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $user;

    public function model()
    {
        return User::class;
    }
}
