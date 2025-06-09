<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    /** @use HasFactory<\Database\Factories\TransferFactory> */
    use HasFactory;

    protected $fillable = [
        'player_id',
        'price',
        'is_transferred',
        'from_team_id',
        'to_team_id',
    ];

    protected $casts = [
        'is_transferred' => 'boolean',
    ];

    protected $attributes = [
        'is_transferred' => false,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getIsTransferred(): bool
    {
        return $this->is_transferred;
    }

    public function getFromTeamId(): int
    {
        return $this->from_team_id;
    }

    public function getToTeamId(): ?int
    {
        return $this->to_team_id;
    }

    public function getPlayerId(): int
    {
        return $this->player_id;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function fromTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'from_team_id');
    }

    public function toTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'to_team_id');
    }

    #[Scope]
    public function activeTransfer(Builder $query): Builder
    {
        return $query->where('is_transferred', false);
    }

    #[Scope]
    public function filterByTeamId(Builder $builder, array $filters): Builder
    {
        return $builder->when(isset($filters['teamId']), static function (Builder $query) use ($filters) {
            $teamId = $filters['teamId'];
            $query->whereHas('fromTeam', static function ($query) use ($teamId) {
                $query->where('id', $teamId);
            });
        });
    }

    #[Scope]
    public function filterByKeyword(Builder $builder, array $filters): Builder
    {
        return $builder->when(isset($filters['keyword']), static function (Builder $query) use ($filters) {
            $keyword = $filters['keyword'];
            $query->whereHas('player', static function ($query) use ($keyword) {
                $query->where('first_name', 'like', '%'.$keyword.'%')
                    ->orWhere('last_name', 'like', '%'.$keyword.'%');
            });
        });
    }
}
