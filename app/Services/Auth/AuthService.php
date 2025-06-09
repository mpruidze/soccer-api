<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Repositories\UsersRepositoryContract;
use App\Models\User;
use App\Services\Service;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService extends Service
{
    public function __construct(
        private readonly UsersRepositoryContract $usersRepository,
        private readonly Connection $db,
    ) {}

    public function register(array $data): array
    {
        $user = $this->db->transaction(function () use ($data) {
            $user = $this->usersRepository->create($data);

            event(new Registered($user));

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

    public function getAuthUser(array $relations = []): User
    {
        /** @var User $user */
        $user = auth()->user();

        if (! empty($relations)) {
            $user->loadMissing($relations);
        }

        return $user;
    }

    private function buildAuthResponse(User $user): array
    {
        return [
            'user' => $user->toArray(),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }
}
