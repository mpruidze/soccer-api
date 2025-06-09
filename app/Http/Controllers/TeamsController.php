<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Teams\TeamUpdateRequest;
use App\Http\Resources\Teams\TeamResource;
use App\Models\Team;
use App\Services\TeamsService;
use Illuminate\Http\JsonResponse;

class TeamsController extends Controller
{
    public function __construct(private readonly TeamsService $teamService) {}

    public function show(Team $team): JsonResponse
    {
        $team = $this->teamService->find($team);

        return $this->response(new TeamResource($team));
    }

    public function update(TeamUpdateRequest $request, Team $team): JsonResponse
    {
        $team = $this->teamService->update($team, $request->validated());

        return $this->response(new TeamResource($team));
    }
}
