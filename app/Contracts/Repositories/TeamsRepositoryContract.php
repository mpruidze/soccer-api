<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Team;

interface TeamsRepositoryContract
{
    public function find(Team $team): Team;

    public function update(Team $team, array $data): Team;
}
