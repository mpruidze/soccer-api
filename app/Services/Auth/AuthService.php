<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Repositories\UsersRepositoryContract;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UsersRepositoryContract $usersRepository,
    ) {}

    public function register(array $data): array
    {
        $user = $this->usersRepository->create($data);

        return $this->buildAuthResponse($user);
    }

    public function login(array $data): array
    {
        if (! Auth::attempt($data)) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        return $this->buildAuthResponse($this->getUser());
    }

    public function logout(): void
    {
        $this->getUser()->currentAccessToken()->delete();
    }

    public function getUser(): ?Authenticatable
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
