<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\UsersRepositoryContract;
use App\Models\User;

class UsersRepository implements UsersRepositoryContract
{
    public function create(array $data): User
    {
        return User::create($data);
    }
}
