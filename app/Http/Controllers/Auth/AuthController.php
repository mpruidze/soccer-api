<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return $this->response($data, __('auth.register_success'), Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->response($data, __('auth.login_success'));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->response(message: __('auth.logout_success'));
    }

    public function getUser(): JsonResponse
    {
        $user = $this->authService->getAuthUser(['team']);

        return $this->response(new UserResource($user));
    }
}
