<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected readonly AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return $this->success($data, __('auth.register_success'), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->success($data, __('auth.login_success'));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->success(message: __('auth.logout_success'));
    }

    public function user(): JsonResponse
    {
        $user = $this->authService->getUser();

        return $this->success(new UserResource($user));
    }
}
