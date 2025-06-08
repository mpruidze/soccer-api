<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Repositories\UsersRepositoryContract;
use App\Models\User;
use App\Services\PlayersService;
use App\Services\TeamsService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UsersRepositoryContract $usersRepository,
        private readonly TeamsService $teamsService,
        private readonly PlayersService $playersService,
        private readonly Connection $db,
    ) {}

    public function register(array $data): array
    {
        $user = $this->db->transaction(function () use ($data) {
            $user = $this->usersRepository->create($data);
            $team = $this->teamsService->generateTeam($user);
            $this->playersService->generatePlayers($team);

            return $user;
        });

        return $this->buildAuthResponse($user);
    }

    public function login(array $data): array
    {
        if (! Auth::attempt($data)) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        return $this->buildAuthResponse($this->getAuthUser());
    }

    public function logout(): void
    {
        $this->getAuthUser()->currentAccessToken()->delete();
    }

    private function getAuthUser(): Authenticatable
    {
        return auth()->user();
    }

    private function buildAuthResponse(User|Authenticatable $user): array
    {
        return [
            'user' => $user->only(['id', 'name', 'email']),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }
}
