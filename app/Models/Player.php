<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'country'];

    protected $attributes = [
        'value' => 1000000.00,
    ];

    protected $casts = [
        'position' => PlayerPosition::class,
    ];

    public function getTranslatedPosition(): string
    {
        return $this->position->translate();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeFilterByTeamId(Builder $builder, array $filters): Builder
    {
        return $builder->when(! empty($filters['teamId']), static function (Builder $query) use ($filters) {
            $teamId = $filters['teamId'];
            $query->whereHas('team', static function ($query) use ($teamId) {
                $query->where('id', $teamId);
            });
        });
    }

    public function scopeFilterByKeyword(Builder $builder, array $filters): Builder
    {
        return $builder->when(! empty($filters['keyword']), static function (Builder $query) use ($filters) {
            $keyword = $filters['keyword'];
            $query->where('firstName', 'like', '%'.$keyword.'%', 'or');
            $query->where('lastName', 'like', $keyword.'%', 'or');
        });
    }
}
