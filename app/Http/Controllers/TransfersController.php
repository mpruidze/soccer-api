<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Transfers\TransferActionRequest;
use App\Http\Requests\Transfers\TransferIndexRequest;
use App\Http\Requests\Transfers\TransferStoreRequest;
use App\Http\Requests\Transfers\TransferUpdateRequest;
use App\Http\Resources\Transfers\TransferCollection;
use App\Http\Resources\Transfers\TransferResource;
use App\Models\Transfer;
use App\Services\Auth\AuthService;
use App\Services\TransfersService;
use Illuminate\Http\JsonResponse;

class TransfersController extends Controller
{
    public function __construct(
        private readonly TransfersService $transfersService,
        private readonly AuthService $authService,
    ) {}

    public function index(TransferIndexRequest $request): JsonResponse
    {
        $filters = $this->getInputFilters();
        $page = $this->getInputPage();
        $perPage = $this->getInputPerPage();

        $transfers = $this->transfersService->findItems($filters, $page, $perPage);

        return $this->response(new TransferCollection($transfers));
    }

    public function store(TransferStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->authService->getAuthUser(['team']);

        $data['from_team_id'] = $user->team->getId();

        $transfer = $this->transfersService->create($data);

        return $this->response(new TransferResource($transfer));
    }

    public function show(Transfer $transfer): JsonResponse
    {
        $transfer = $this->transfersService->find($transfer);

        return $this->response(new TransferResource($transfer));
    }

    public function update(TransferUpdateRequest $request, Transfer $transfer): JsonResponse
    {
        $transfer = $this->transfersService->update($transfer, $request->validated());

        return $this->response(new TransferResource($transfer));
    }

    public function confirm(TransferActionRequest $request, Transfer $transfer): JsonResponse
    {
        $data = $request->validated();
        $user = $this->authService->getAuthUser(['team']);

        $data['to_team_id'] = $user->team->getId();
        unset($data['action']);

        $transfer = $this->transfersService->confirm($transfer, $data);

        return $this->response(new TransferResource($transfer));
    }
}
