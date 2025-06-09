<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\User;

interface UsersRepositoryContract
{
    public function create(array $data): User;
}
