<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\TeamsRepositoryContract;
use App\Models\Team;
use App\Models\User;

class TeamsService extends Service
{
    public function __construct(
        private readonly TeamsRepositoryContract $teamsRepository,
    ) {}

    public function find(Team $team): Team
    {
        $this->authorize('view', $team);

        return $this->teamsRepository->find($team);
    }

    public function update(Team $team, array $data): Team
    {
        $this->authorize('update', $team);

        return $this->teamsRepository->update($team, $data);
    }

    public function generateTeam(User $user): Team
    {
        return Team::factory()->for($user)->create();
    }
}
