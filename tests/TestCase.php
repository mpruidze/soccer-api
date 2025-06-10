<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\PlayerPosition;
use App\Models\Player;
use App\Models\Team;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function createUsers(array $attributes = [], int $count = 1): Collection
    {
        return User::factory()->count($count)->create($attributes);
    }

    public function createUsersWithTeamAndPlayers(array $attributes = [], int $count = 1): Collection
    {
        $users = $this->createUsers($attributes, $count);

        foreach ($users as $user) {
            $team = $this->createTeamForUser($user)->first();
            $this->createPlayersForTeam($team);
        }

        return $users;
    }

    public function createAndAuthenticateUser(array $attributes = []): User
    {
        $user = $this->createUsers($attributes)->first();
        Sanctum::actingAs($user);

        return $user;
    }

    public function createAndAuthenticateUserWithTeamAndPlayers(): User
    {
        $user = $this->createUsersWithTeamAndPlayers()->first();
        Sanctum::actingAs($user);

        return $user;
    }

    public function createTeamForUser(User $user, array $attributes = [], int $count = 1): Collection
    {
        return Team::factory()->for($user)->count($count)->create($attributes);
    }

    public function createPlayerForTeam(Team $team, array $attributes = [], int $count = 1): Collection
    {
        return Player::factory()->for($team)->count($count)->create($attributes);
    }

    public function createPlayersForTeam(Team $team, array $attributes = []): Collection
    {
        $playerData = [
            PlayerPosition::GOALKEEPER->value => 3,
            PlayerPosition::DEFENDER->value => 6,
            PlayerPosition::MIDFIELDER->value => 6,
            PlayerPosition::ATTACKER->value => 5,
        ];

        $players = collect();

        foreach ($playerData as $position => $count) {
            $createdPlayers = $this->createPlayerForTeam($team, array_merge($attributes, [
                'position' => $position,
            ]), $count);

            $players = $players->merge($createdPlayers);
        }

        return $players;
    }

    public function createTransfers(int $count = 1, ?User $user = null, array $attributes = []): Collection
    {
        $transfers = collect();

        for ($i = 0; $i < $count; $i++) {
            $user ??= $this->createUsersWithTeamAndPlayers()->first();

            $transfer = Transfer::factory()->create(array_merge($attributes, [
                'from_team_id' => $user->team->getId(),
                'player_id' => $user->players->first()->getId(),
            ]));
            $transfers->push($transfer);
        }

        return $transfers;
    }

    public function userStructure(): array
    {
        return [
            'data' => [
                'id',
                'name',
                'email',
            ],
        ];
    }

    public function teamStructure(): array
    {
        return [
            'data' => [
                'id',
                'name',
                'country',
                'budget',
                'value',
            ],
        ];
    }

    public function playerStructure(): array
    {
        return [
            'data' => [
                'id',
                'firstName',
                'lastName',
                'age',
                'country',
                'position',
                'value',
            ],
        ];
    }

    public function transferStructure(): array
    {
        return [
            'data' => [
                'id',
                'price',
                'isTransferred',
            ],
        ];
    }

    public function transferListStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'id',
                    'price',
                    'isTransferred',
                ],
            ],
        ];
    }
}
